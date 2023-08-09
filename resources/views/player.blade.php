
<!DOCTYPE html>
<html>

<head>
    <base href="/" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- web font loader -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

    {{--<link href="https://vjs.zencdn.net/6.4.0/video-js.css" rel="stylesheet">--}}
    <link href="https://vjs.zencdn.net/7.4.1/video-js.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    @if($playerEnv !== 'local')
        <link href="{!! $playerStyleUrl !!}" rel="stylesheet">
    @endif

    <script>
        window.playerConfig = {
            project: {!! $project !!},
            apiUrl: '{!! $apiUrl !!}',
            playing: {!! $playing !!},
            env: '{!! $env !!}',
            playerEnv: '{!! $playerEnv !!}'
        }
    </script>

    <style>
        html,
        body,
        #appRoot {
            background: black;
            height: 100%;
            margin: 0;
        }
    </style>

    <script src="https://vjs.zencdn.net/7.4.1/video.js"></script>
    <script src="https://s3.us-east-2.amazonaws.com/static.videosuite.io/Youtube.min.js"></script>
</head>

<body>
<div id="app"></div>
<script src="{!! $playerScriptUrl !!}"></script>
</body>

</html>
