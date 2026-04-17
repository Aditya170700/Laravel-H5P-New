/**
 * Wraps H5PEditor.FileUploader to send large files in smaller POSTs, then assembles on the server.
 * Requires H5PIntegration.editor.chunkUpload (injected via View::composer) and _h5pEditorUploadField on instances.
 */
(function () {
  if (
    typeof H5PEditor === "undefined" ||
    typeof H5PEditor.FileUploader === "undefined"
  ) {
    return;
  }

  var cfg =
    (typeof H5PIntegration !== "undefined" &&
      H5PIntegration.editor &&
      H5PIntegration.editor.chunkUpload) ||
    {};
  if (!cfg.enabled) {
    return;
  }

  function resolveUploadUrl(suffix) {
    var ajaxPath =
      (H5PIntegration.editor && H5PIntegration.editor.ajaxPath) || "/ajax/";
    try {
      return new URL("files/" + suffix, ajaxPath).href;
    } catch (e) {
      return ajaxPath + "files/" + suffix;
    }
  }

  function postFormData(url, formData, onProgress) {
    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", url, true);
      xhr.withCredentials = true;
      xhr.upload.onprogress = function (e) {
        if (onProgress && e.lengthComputable) {
          onProgress(e.loaded / e.total);
        }
      };
      xhr.onload = function () {
        var text = xhr.responseText || "";
        if (xhr.status < 200 || xhr.status >= 300) {
          reject(new Error(text || "HTTP " + xhr.status));
          return;
        }
        try {
          var json = JSON.parse(text);
          if (json && json.success === false) {
            reject(new Error(json.message || "Upload failed"));
            return;
          }
        } catch (ignore) {
          /* chunk endpoint returns JSON; tolerate non-JSON */
        }
        resolve(text);
      };
      xhr.onerror = function () {
        reject(new Error("Network error"));
      };
      xhr.send(formData);
    });
  }

  var Original = H5PEditor.FileUploader;

  function WrappedFileUploader(field) {
    this._h5pEditorUploadField = field;
    Original.call(this, field);
    var self = this;
    var innerUpload = self.upload;

    self.upload = function (file, filename) {
      var threshold = cfg.thresholdBytes || 5242880;
      var chunkSize = cfg.chunkBytes || 5242880;

      if (!file || file.size <= threshold) {
        return innerUpload.call(self, file, filename);
      }

      var uploadField = self._h5pEditorUploadField;
      var fieldJson = JSON.stringify(uploadField || {});
      var contentId =
        typeof H5PEditor.contentId !== "undefined" &&
        H5PEditor.contentId !== null
          ? H5PEditor.contentId
          : 0;

      var uploadId =
        typeof crypto !== "undefined" && crypto.randomUUID
          ? crypto.randomUUID()
          : "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
              /[xy]/g,
              function (c) {
                var r = (Math.random() * 16) | 0;
                var v = c === "x" ? r : (r & 0x3) | 0x8;
                return v.toString(16);
              },
            );

      var chunkUrl = resolveUploadUrl("chunk");
      var assembleUrl = resolveUploadUrl("chunk-assemble");
      var total = Math.max(1, Math.ceil(file.size / chunkSize));

      self.trigger("upload");

      (function run() {
        var offset = 0;
        var index = 0;
        var next = function () {
          if (offset >= file.size) {
            var fd2 = new FormData();
            fd2.append("upload_id", uploadId);
            fd2.append("total_chunks", String(total));
            fd2.append("field", fieldJson);
            fd2.append("contentId", String(contentId));
            fd2.append("filename", filename);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", assembleUrl, true);
            xhr.withCredentials = true;
            xhr.onload = function () {
              var uploadComplete = { error: null, data: null };
              var result;
              try {
                result = JSON.parse(xhr.responseText);
              } catch (err) {
                H5P.error(err);
                uploadComplete.error = H5PEditor.t("core", "fileToLarge");
              }
              if (xhr.status < 200 || xhr.status >= 300) {
                uploadComplete.error =
                  result && result.message
                    ? result.message
                    : H5PEditor.t("core", "unknownFileUploadError");
              }
              if (result !== undefined) {
                if (result.error !== undefined) {
                  uploadComplete.error = result.error;
                }
                if (result.success === false) {
                  uploadComplete.error = result.message
                    ? result.message
                    : H5PEditor.t("core", "unknownFileUploadError");
                }
              }
              if (uploadComplete.error === null) {
                uploadComplete.data = result;
              }
              self.trigger("uploadComplete", uploadComplete);
            };
            xhr.onerror = function () {
              self.trigger("uploadComplete", {
                error: H5PEditor.t("core", "unknownFileUploadError"),
                data: null,
              });
            };
            xhr.send(fd2);
            return;
          }

          var end = Math.min(offset + chunkSize, file.size);
          var blob = file.slice(offset, end);
          var fd = new FormData();
          fd.append("chunk", blob, filename);
          fd.append("upload_id", uploadId);
          fd.append("chunk_index", String(index));
          fd.append("total_chunks", String(total));
          fd.append("filename", filename);

          postFormData(chunkUrl, fd, function (partRatio) {
            var doneBefore = index * chunkSize;
            var cur = doneBefore + partRatio * (end - offset);
            self.trigger("uploadProgress", Math.min(1, cur / file.size));
          })
            .then(function () {
              offset = end;
              index += 1;
              self.trigger("uploadProgress", offset / file.size);
              next();
            })
            .catch(function (err) {
              self.trigger("uploadComplete", {
                error:
                  err && err.message
                    ? err.message
                    : H5PEditor.t("core", "unknownFileUploadError"),
                data: null,
              });
            });
        };
        next();
      })();
    };
  }

  WrappedFileUploader.prototype = Original.prototype;
  WrappedFileUploader.prototype.constructor = WrappedFileUploader;
  H5PEditor.FileUploader = WrappedFileUploader;
  if (typeof window.ns !== "undefined") {
    ns.FileUploader = WrappedFileUploader;
  }
})();
