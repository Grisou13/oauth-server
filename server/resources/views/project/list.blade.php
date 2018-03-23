@extends("layout")
@section("content")
    <div class="container">

    </div>
    @empty($projects)
        <h3>Seems like you don't have any projects.</h3>
        <a href="{{ url("/dashboard/projects/create") }}" class="btn">Create one</a>
    @else
        <a href="{{ url("/dashboard/projects/create") }}" class="btn">Create another project</a>
    @endempty
    @foreach($projects as $project)
        <div class="row">
            <h2>{{ $project->name }}</h2>
        </div>
    @endforeach
@endsection