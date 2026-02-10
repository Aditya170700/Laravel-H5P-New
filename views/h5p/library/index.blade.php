@extends(config('laravel-h5p.layout'))

@section('h5p')
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-9">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <form action="{{ route('h5p.library.store') }}" method="POST" id="h5p-library-form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="h5p-file"
                                    class="control-label">{{ trans('laravel-h5p.library.upload_libraries') }}</label>

                                <div class="well well-sm text-center" style="border: 2px dashed #ccc; padding: 30px;">
                                    <span class="glyphicon glyphicon-cloud-upload"
                                        style="font-size: 48px; color: #999;"></span>
                                    <div class="help-block">
                                        <label for="h5p-file" class="btn btn-link" style="cursor: pointer;">
                                            <span>Upload a file</span>
                                        </label>
                                        or drag and drop
                                    </div>
                                    <p class="text-muted small">.h5p files only</p>
                                    <input id="h5p-file" name="h5p_file" type="file" class="hidden" accept=".h5p">
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="h5p_upgrade_only" id="h5p-upgrade-only">
                                        {{ trans('laravel-h5p.library.only_update_existing_libraries') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="h5p_disable_file_check" id="h5p-disable-file-check">
                                        {{ trans('laravel-h5p.library.upload_disable_extension_check') }}
                                    </label>
                                </div>

                                @if ($errors->has('h5p_file'))
                                    <p class="text-danger help-block">
                                        {{ $errors->first('h5p_file') }}
                                    </p>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    {{ trans('laravel-h5p.library.upload') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <form action="{{ route('h5p.library.clear') }}" method="POST"
                            id="laravel-h5p-update-content-type-cache" enctype="multipart/form-data">
                            @csrf

                            <h4 class="panel-title">{{ trans('laravel-h5p.library.content_type_cache') }}</h4>

                            <div class="form-group" style="margin-top: 15px;">
                                <input type="hidden" id="sync_hub" name="sync_hub" value="">
                                <button type="submit" name="updatecache" id="updatecache" class="btn btn-danger btn-block">
                                    {{ trans('laravel-h5p.library.clear') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p class="panel-title">{{ trans('laravel-h5p.library.search-result', ['count' => count($entrys)]) }}
                        </p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="active">
                                    <th class="text-left">{{ trans('laravel-h5p.library.name') }}</th>
                                    <th class="text-center">{{ trans('laravel-h5p.library.version') }}</th>
                                    <th class="text-center">{{ trans('laravel-h5p.library.restricted') }}</th>
                                    <th class="text-center">{{ trans('laravel-h5p.library.contents') }}</th>
                                    <th class="text-center">{{ trans('laravel-h5p.library.contents_using_it') }}</th>
                                    <th class="text-center">{{ trans('laravel-h5p.library.libraries_using_it') }}</th>
                                    <th class="text-center">{{ trans('laravel-h5p.library.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @unless (count($entrys) > 0)
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            {{ trans('laravel-h5p.common.no-result') }}</td>
                                    </tr>
                                @endunless

                                @foreach ($entrys as $entry)
                                    <tr>
                                        <td class="text-left">
                                            {{ $entry->title }}
                                        </td>
                                        <td class="text-center">
                                            {{ $entry->major_version . '.' . $entry->minor_version . '.' . $entry->patch_version }}
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" value="{{ $entry->restricted }}"
                                                @if ($entry->restricted == '1') checked @endif
                                                class="laravel-h5p-restricted" data-id="{{ $entry->id }}">
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($entry->numContent()) }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($entry->getCountContentDependencies()) }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($entry->getCountLibraryDependencies()) }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="laravel-h5p-destory btn btn-danger btn-xs"
                                                data-id="{{ $entry->id }}">
                                                {{ trans('laravel-h5p.library.remove') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-footer">
                        {!! $entrys->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('h5p-header')
    {{--    core styles       --}}
    @foreach ($settings['core']['styles'] as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach
@endpush

@push('h5p-footer')
    <script type="text/javascript">
        H5PAdminIntegration = {!! json_encode($settings) !!};
    </script>

    {{--    core script       --}}
    @foreach ($required_files['scripts'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach

    <script type="text/javascript">
        (function($) {

            if (!$) {
                console.warn("jQuery not loaded for H5P library management.");
                return;
            }

            $(document).ready(function() {

                // File input change handler to show filename
                $('#h5p-file').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    if (fileName) {
                        $(this).closest('.well').find('label.btn-link span').first().text(fileName);
                    } else {
                        $(this).closest('.well').find('label.btn-link span').first().text(
                            'Upload a file');
                    }
                });

                $(document).on("click", ".laravel-h5p-restricted", function(e) {
                    var $this = $(this);
                    $.ajax({
                        url: "{{ route('h5p.library.restrict') }}",
                        data: {
                            id: $this.data('id'),
                            selected: $this.is(':checked')
                        },
                        success: function(response) {
                            alert("{{ trans('laravel-h5p.library.updated') }}");
                        },
                        error: function() {
                            alert("Error updating restriction status");
                            $this.prop('checked', !$this.is(':checked'));
                        }
                    });
                });

                $(document).on("submit", "#laravel-h5p-update-content-type-cache", function(e) {
                    if (confirm("{{ trans('laravel-h5p.library.confirm_clear_type_cache') }}")) {
                        return true;
                    } else {
                        return false;
                    }
                });

                $(document).on("click", ".laravel-h5p-destory", function(e) {
                    var $obj = $(this);
                    var msg = "{{ trans('laravel-h5p.library.confirm_destroy') }}";
                    if (confirm(msg)) {
                        $.ajax({
                            url: "{{ route('h5p.library.destroy') }}",
                            data: {
                                id: $obj.data('id'),
                                _token: "{{ csrf_token() }}"
                            },
                            method: "DELETE",
                            success: function(response) {
                                if (response.msg) {
                                    alert(response.msg);
                                }
                                location.reload();
                            },
                            error: function() {
                                alert(
                                "{{ trans('laravel-h5p.library.can_not_destroy') }}");
                                location.reload();
                            }
                        });
                    }
                });

            });

        })(window.H5P && window.H5P.jQuery ? window.H5P.jQuery : window.jQuery);
    </script>
@endpush
