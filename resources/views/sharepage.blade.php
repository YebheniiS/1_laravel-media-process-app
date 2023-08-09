<!DOCTYPE html>
<html>

<head>
    <base href="/" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" media="all">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css"></link>
    <!-- For react-semantic-ui -->
    <!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.12/semantic.min.css"></link>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500, 500i,600,600i,700" rel="stylesheet">
    <!-- for react-tinymce-input -->
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <!-- web font loader -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

    <link href="//d26b395fwzu5fz.cloudfront.net/keen-dataviz-1.2.1.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/6.3.3/video-js.css" rel="stylesheet">

    <link href="/main.css" rel="stylesheet">

    <meta property="og:title" content="{!! $project->title !!}">
    <meta property="og:description" content="{!! $project->description !!}">
    <meta property="og:image" content="{!! $project->facebook_image_url !!}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:type" content="article">

    <meta property="name" content="{!! $project->title !!}">
    <meta property="description" content="{!! $project->description !!}">
    <meta property="image" content="{!! $project->google_image_url !!}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! $project->title!!}">
    <meta name="twitter:description" content="{!! $project->description !!}">
    <meta name="twitter:image:src" content="{!! $project->twitter_image_url !!}">

    <style>
        #appRoot, html, body {
            min-width:  100% !important;
        }
    </style>
</head>

<body>
<div id="appRoot"></div>
<script src="/main.js"></script>

<!-- Needed for the share project page -->
<!--<script src="https://s3.us-east-2.amazonaws.com/cdn6.swiftcdn.co/build/player/wrapper.js"></script>-->
</body>

</html>