<div id="app" class="container">
    <h1>Welcome to your dashboard</h1>
    <div class="row">
        <h2>Manage your apis</h2>
        <router-link tag="button" to="/clients"><a href="#" class="btn col s12"> Manager your api clients</a></router-link>
    </div>
    <div class="row">
        <h2>Manage your api clients</h2>
        <router-link tag="button" to="/projects"><a href="#" class="btn col s12"> Manager your apis</a></router-link>
    </div>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
</div>

@push("scripts")

@endpush


<html>
<head>
    <script>
        window.BASE_URL="{{ url("/") }}"
    </script>
    @stack("styles")
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

    <script src="{{ url("build.js") }}"></script>
    @stack("scripts")

</body>
</html>
