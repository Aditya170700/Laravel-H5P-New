@extends(config('laravel-h5p.layout'))

@section('h5p')
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-12">

                <div class="h5p-content-wrap">
                    {!! $embed_code !!}
                </div>

                <br />
                <p class='text-center'>

                    <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fa fa-reply"></i>
                        {{ trans('laravel-h5p.content.cancel') }}</a>

                </p>
            </div>

        </div>

    </div>
@endsection

@push('h5p-header-script')
    {{--    core styles       --}}
    @foreach ($settings['core']['styles'] as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach

    @foreach ($settings['loadedCss'] as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach
@endpush

@push('h5p-footer-script')
    @php
        $h5pL10nDefaults = [
            'reuse' => 'Reuse',
            'reuseContent' => 'Reuse Content',
            'reuseDescription' => 'Reuse this content.',
            'embed' => 'Embed',
            'embedDescription' => 'View the embed code for this content.',
            'download' => 'Download',
            'downloadDescription' => 'Download this content as a H5P file.',
            'copyrights' => 'Copyrights',
            'copyrightsDescription' => 'View copyright information for this content.',
            'h5pDescription' => 'Visit H5P.org to check out more cool content.',
        ];
        if (!isset($settings['l10n'])) {
            $settings['l10n'] = [];
        }
        if (!isset($settings['l10n']['H5P'])) {
            $settings['l10n']['H5P'] = [];
        }
        foreach ($h5pL10nDefaults as $key => $value) {
            if (empty($settings['l10n']['H5P'][$key])) {
                $settings['l10n']['H5P'][$key] = $value;
            }
        }
    @endphp
    <script type="text/javascript">
        H5PIntegration = {!! json_encode($settings) !!};
        (function() {
            var ns = 'H5P';
            if (!H5PIntegration.l10n) H5PIntegration.l10n = {};
            if (!H5PIntegration.l10n[ns]) H5PIntegration.l10n[ns] = {};
            var fallback = {
                reuse: 'Reuse',
                reuseContent: 'Reuse Content',
                reuseDescription: 'Reuse this content.',
                embed: 'Embed',
                embedDescription: 'View the embed code for this content.',
                download: 'Download',
                downloadDescription: 'Download this content as a H5P file.',
                copyrights: 'Copyrights',
                copyrightsDescription: 'View copyright information for this content.',
                h5pDescription: 'Visit H5P.org to check out more cool content.'
            };
            for (var k in fallback) {
                if (!H5PIntegration.l10n[ns][k] || H5PIntegration.l10n[ns][k] === '') {
                    H5PIntegration.l10n[ns][k] = fallback[k];
                }
            }
        })();
    </script>

    {{--    core script       --}}
    @foreach ($settings['core']['scripts'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach

    @foreach ($settings['loadedJs'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach

    <script type="text/javascript">
        (function() {
            var H5PL10nFallback = {
                reuse: 'Reuse',
                reuseContent: 'Reuse Content',
                reuseDescription: 'Reuse this content.',
                embed: 'Embed',
                embedDescription: 'View the embed code for this content.',
                download: 'Download',
                downloadDescription: 'Download this content as a H5P file.',
                copyrights: 'Copyrights',
                copyrightsDescription: 'View copyright information for this content.',
                h5pDescription: 'Visit H5P.org to check out more cool content.'
            };

            function applyFallback() {
                if (typeof H5PIntegration === 'undefined') return;
                var ns = 'H5P';
                if (!H5PIntegration.l10n) H5PIntegration.l10n = {};
                if (!H5PIntegration.l10n[ns]) H5PIntegration.l10n[ns] = {};
                for (var k in H5PL10nFallback) {
                    if (!H5PIntegration.l10n[ns][k] || H5PIntegration.l10n[ns][k] === '') {
                        H5PIntegration.l10n[ns][k] = H5PL10nFallback[k];
                    }
                }
                if (typeof H5P !== 'undefined' && H5P.t && !H5P.t._h5pL10nPatched) {
                    var orig = H5P.t;
                    H5P.t = function(key, vars, ns) {
                        var r = orig.apply(this, arguments);
                        if (typeof r === 'string' && r.indexOf('[Missing translation') === 0 && H5PL10nFallback[
                            key]) {
                            return H5PL10nFallback[key];
                        }
                        return r;
                    };
                    H5P.t._h5pL10nPatched = true;
                }
            }
            applyFallback();
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyFallback);
            }
            setTimeout(applyFallback, 100);
        })();
    </script>
@endpush
