@extends("layout")

@section("content")
    <div class="container">
        <div class="row">
            <h2>Manage your apis</h2>
            <a href="{{ url("/dashboard/projects") }}" class="btn col s12"></a>
        </div>
        <div class="row">
            <h2>Manage your api clients</h2>
            <a href="{{ url("/dashboard/clients") }}" class="btn col s12"></a>
        </div>
    </div>
@endsection