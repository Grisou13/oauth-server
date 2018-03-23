@extends("layout")
@section("content")
    <div class="container">

    </div>
    @if($project->exists)
        <form action="{{ url("/dashboard/projects/".$project->id) }}" method="post">
            <input type="hidden" name="_method" value="PUT" />
    @else
        <form action="{{ url("/dashboard/projects") }}" method="post">
    @endif
            <input type="text" name="name">
            <input type="text" name="description">
            <div id="scope-table"></div>
        </form>

@endsection
@push("scripts")

@endpush