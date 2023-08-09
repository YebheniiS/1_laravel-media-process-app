<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Interactr</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        input {
            font-size: 21px;
            width: 500px;
        }

    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            Enter your transaction ID from JVZOO.
        </div>
        <p>
            If you view your purchases in JVZOO under Interactr Evolution you will see a Payment ID reference, it should look like this: AP-1234567890ABCDEFG.<br>
            You can also see this in the email receipt you received when purchasing Interactr Pro
        </p>
        <form action="/register/checkTransactionId" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="email" value="{!! $email !!}">
            <div class="form-group">
                <input type="text" class="form-control" name="transaction_id" id="transaction_id" aria-describedby="emailHelp" placeholder="AP-1234567890ABCDEFG">
            </div>
            <button type="submit" class="btn btn-primary">Check Transaction ID</button>
        </form>
    </div>
</div>
</body>
</html>
