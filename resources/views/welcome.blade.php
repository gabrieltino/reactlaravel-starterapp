<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>

<body>
    @if(config('app.env') == 'local')
    <script src="http://localhost:35729/livereload.js"></script>
    @endif


    <div id="app"></div>
    <script src="/js/app.js"></script>
</body>

</html>