<html>
<head>
    <script>
        window.BASE_URL="{{ url("/") }}"
    </script>
    <meta name="auth-token" value="{{ $authToken or null }}" />
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPNV Authorization server</title>
    <link rel="stylesheet" href="{{ url("css/materialize.min.css") }}">
    <link rel="stylesheet" href="{{ url("css/app.css") }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="grey lighten-5 ">
    @include("_menu")
    <div >
        @yield("content")
    </div>

    @stack("scripts")
    <script src="{{ url("js/vendor/jquery.js") }}"></script>
    <script src="{{ url("js/vendor/what-input.js") }}"></script>
    <script src="{{ url("js/vendor/materialize.min.js") }}"></script>
    <script src="{{ url("js/app.js") }}"></script>
</body>
</html>