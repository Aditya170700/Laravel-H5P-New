@extends( config('laravel-h5p.layout') )

@section( 'h5p' )
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12 md:col-span-9">

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6 border border-gray-200 dark:border-gray-700">

                <form action="{{ route('h5p.library.store') }}" method="POST" id="h5p-library-form" class="p-6" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="h5p-file" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">{{ trans('laravel-h5p.library.upload_libraries') }}</label>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="h5p-file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="h5p-file" name="h5p_file" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    .h5p files only
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="h5p_upgrade_only" id="h5p-upgrade-only" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-indigo-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="h5p-upgrade-only" class="font-medium text-gray-700 dark:text-gray-300">{{ trans('laravel-h5p.library.only_update_existing_libraries') }}</label>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="h5p_disable_file_check" id="h5p-disable-file-check" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-indigo-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="h5p-disable-file-check" class="font-medium text-gray-700 dark:text-gray-300">{{ trans('laravel-h5p.library.upload_disable_extension_check') }}</label>
                                </div>
                            </div>
                        </div>

                        @if ($errors->has('h5p_file'))
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                            {{ $errors->first('h5p_file') }}
                        </p>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <input type="submit" name="submit" value="{{ trans('laravel-h5p.library.upload') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 cursor-pointer">
                    </div>
                </form>

            </div>

        </div>
        <div class="col-span-12 md:col-span-3">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6 border border-gray-200 dark:border-gray-700">

                <form action="{{ route('h5p.library.clear') }}" method="POST" id="laravel-h5p-update-content-type-cache" class="p-6" enctype="multipart/form-data">
                    @csrf

                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ trans('laravel-h5p.library.content_type_cache') }}</h4>

                    <div class="flex items-center justify-end">
                        <input type="hidden" id="sync_hub" name="sync_hub" value="">
                        <input type="submit" name="updatecache" id="updatecache" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 cursor-pointer" value="{{ trans('laravel-h5p.library.clear') }}">
                    </div>
                </form>

            </div>

        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <p class="text-gray-700 dark:text-gray-300">
                {{ trans('laravel-h5p.library.search-result', ['count' => count($entrys)]) }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.name') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.version') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.restricted') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.contents') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.contents_using_it') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.libraries_using_it') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ trans('laravel-h5p.library.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @unless(count($entrys) >0)
                    <tr><td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">{{ trans('laravel-h5p.common.no-result') }}</td></tr>
                    @endunless

                    @foreach($entrys as $entry)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $entry->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                            {{ $entry->major_version.'.'.$entry->minor_version.'.'.$entry->patch_version }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            <input type="checkbox" value="{{ $entry->restricted }}"
                                   @if($entry->restricted == '1')
                                   checked=""
                                   @endif
                                   class="laravel-h5p-restricted focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-indigo-600" data-id="{{ $entry->id }}">
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($entry->numContent()) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($entry->getCountContentDependencies()) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($entry->getCountLibraryDependencies()) }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button class="laravel-h5p-destory inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" data-id="{{ $entry->id }}">{{ trans('laravel-h5p.library.remove') }}</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            {!! $entrys->links() !!}
        </div>
    </div>

</div>
@endsection

@push( 'h5p-header' )
{{--    core styles       --}}
@foreach($settings['core']['styles'] as $style)
<link rel="stylesheet" href="{{ $style }}">
@endforeach
@endpush

@push( 'h5p-footer' )
<script type="text/javascript">
    H5PAdminIntegration = {!! json_encode($settings) !!};
</script>

{{--    core script       --}}
@foreach($required_files['scripts'] as $script)
<script src="{{ $script }}"></script>
@endforeach



<script type="text/javascript">

    (function ($) {
        
        if (!$) {
            console.warn("jQuery not loaded for H5P library management.");
            return;
        }

        $(document).ready(function () {
            
            // File input change handler to show filename
            $('#h5p-file').on('change', function() {
                var fileName = $(this).val().split('\\').pop(); 
                if(fileName) {
                    $(this).prev('span').text(fileName);
                } else {
                    $(this).prev('span').text('Upload a file');
                }
            });

            $(document).on("click", ".laravel-h5p-restricted", function (e) {
                var $this = $(this);
                $.ajax({
                    url: "{{ route('h5p.library.restrict') }}",
                    data: {id: $this.data('id'), selected: $this.is(':checked')},
                    success: function (response) {
                        alert("{{ trans('laravel-h5p.library.updated') }}");
                    },
                    error: function() {
                        alert("Error updating restriction status");
                        $this.prop('checked', !$this.is(':checked')); // Revert
                    }
                });
            });

            $(document).on("submit", "#laravel-h5p-update-content-type-cache", function (e) {
                if(confirm("{{ trans('laravel-h5p.library.confirm_clear_type_cache') }}")) {
                        return true;
                }else{
                        return false;
                }
            });

            $(document).on("click", ".laravel-h5p-destory", function (e) {

                    var $obj = $(this);
                    var msg = "{{ trans('laravel-h5p.library.confirm_destroy') }}";
                    if (confirm(msg)) {

                        $.ajax({
                            url: "{{ route('h5p.library.destroy') }}",
                            data: {id: $obj.data('id'), _token: "{{ csrf_token() }}"}, // Ensure CSRF token is sent
                            method: "DELETE",
                            success: function (response) {
                                    if (response.msg) {
                                        alert(response.msg);
                                    }
                                    location.reload();
                            },
                            error: function () {
                                alert("{{ trans('laravel-h5p.library.can_not_destroy') }}");
                                location.reload();
                            }
                        })
                    }

            });

        });

    })(window.H5P && window.H5P.jQuery ? window.H5P.jQuery : window.jQuery);

</script>

@endpush
