<html>
<head>
    <meta name="auth-token" value="{{ $authToken or null }}" />
</head>
<body>
    <h1>
        Welcome
    </h1>
    @yield("content")
    <script src="build.js"></script>
</body>
</html>