@extends("layout")

@section("content")
    <form method="post" action="/login" >
        <input type="text" name="credential" >
        <input type="password" name="password" >
        <button><span>Log in</span></button>
    </form>
@endsection