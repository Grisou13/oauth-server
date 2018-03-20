@extends("layout")

@section("content")
    <form method="post" action="/login?{{ \Illuminate\Support\Facades\Request::getQueryString() }}" >
        <input type="text" name="credential" >
        <input type="password" name="password" >
        <button><span>Log in</span></button>
    </form>
    <button type="button"><a href="/register?{{ \Illuminate\Support\Facades\Request::getQueryString() }}">Register</a></button>
@endsection
