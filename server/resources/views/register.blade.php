@extends("layout")

@section("content")
    <form method="post" action="/register?{{ \Illuminate\Support\Facades\Request::getQueryString() }}" >
        <label>Email: </label><input type="text" name="credential" >
        <label>Password: </label><input type="password" name="password" >
        <label>Confirm password: </label><input type="password" name="password_confirm" >
        <button type="submit"><span>Register</span></button>
    </form>
@endsection
