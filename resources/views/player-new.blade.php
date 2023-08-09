<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Interactive Video Player</title>

    <!-- web font loader -->
{{--    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">--}}

    <link href="https://fonts.googleapis.com/css?family={!! $project->font !!}&display=swap" rel="stylesheet">

    <script src="https://a-fast.b-cdn.net/shared/animations.js"></script>

@if($playerEnv==='local1' || $playerEnv === 'local2' )
    <!-- vue-devtools standalone for debuggin INSIDE of iframe -->
        <script src="http://localhost:8098"></script>
@elseif($playerEnv=== 'browserstack')
    <!-- vue-devtools standalone for debuggin INSIDE of iframe -->
        <script src="http://bs-local.com:8098"></script>

    @else
        {{-- add css only in production env   --}}
        <link href="{!! $playerRoot !!}/css/app.css" rel="stylesheet">
        <link href="{!! $playerRoot !!}/css/chunk-vendors.css" rel="stylesheet">
    @endif

    <script>
        // Gets the project JSON from the window object in production as it faster than an API call
        window.dataSource = 'window';

        // Controls if we post analytics, normally we wouldn't when previewing or debugging
        window.useAnalytics = {!! $analytics ? 'true' : 'false' !!};

        // Controls if we post Fb Pixel, normally we wouldn't when previewing or debugging
        window.useFbPixel = {!! $analytics  ? 'true' : 'false' !!};

        // Window data object, needed when dataSource is set to window. We can also set dataSource to api and window.projectId to
        // get the data remotely, we don't do this in production as it's slower than getting from the window object.
        window.data = {
            project: {!! $project !!},
            videos: {!! $videos !!},
            nodes: {!! $nodes !!},
            modals: {!! $modals !!},
            sharePageUrl: {!! $sharePageUrl !!}
        };

        window.playerConfig = {
            apiUrl: '{!! env('API_URL') !!}',
            analytics: {
                url: '{!! env('ANALYTICS_URL') !!}',
                key: '{!! env('ANALYTICS_KEY') !!}'
            }
        };
    </script>

    <style>
        * {
            font-family: '{!! $project->font !!}', sans-serif;
        }
    </style>

</head>
<body style="background: #ccc">
<noscript>
    <strong>We're sorry but pdqplayer doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
<div id="app"></div>
<!-- built files will be auto injected -->
@if($playerEnv==='local1')
    {{-- Player env  in the url of the preview overrides the the env file for testing --}}
    <script src="https://pdqplayer.test/dist/js/app.js"></script>
    <script src="https://pdqplayer.test/dist/js/chunk-vendors.js"></script>
@elseif($playerEnv==='local2')
    {{-- Player env  in the url of the preview overrides the the env file for testing --}}
    <script src="https://localhost:8080/js/app.js"></script>
    <script src="https://localhost:8080/js/chunk-vendors.js"></script>
@elseif($playerEnv === 'browserstack-ios')
    {{-- Browserstack testing env for ios doesn't support localhost , bs-local.com is used for the host in that case --}}
    <script src="https://bs-local.com:8080/js/app.js"></script>
    <script src="https://bs-local.com:8080/js/chunk-vendors.js"></script>
@else
    <script src="{!! $playerRoot !!}/js/app.js"></script>
    <script src="{!! $playerRoot !!}/js/chunk-vendors.js"></script>
@endif
</body>
</html>
