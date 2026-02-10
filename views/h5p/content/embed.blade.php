<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
    </script>

    {{--    core styles       --}}
    @foreach ($settings['core']['styles'] as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach

    @foreach ($settings['loadedCss'] as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach
</head>

<body>

    <div id="app">
        {!! $embed_code !!}
    </div>

    <script type="text/javascript" src="{{ url('/assets/js/app.js') }}"></script>
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
    </script>

    {{--    core script       --}}
    @foreach ($settings['core']['scripts'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach

    @foreach ($settings['loadedJs'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach

</body>

</html>
