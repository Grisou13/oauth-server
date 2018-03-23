<div id="app">
    <h1>Welcome to your dashboard</h1>
    <div class="row">
        <h2>Manage your apis</h2>
        <router-link to="/clients">Manager your api clients</router-link>
    </div>
    <div class="row">
        <h2>Manage your api clients</h2>
        <router-link to="/projects">Manager your apis</router-link>
    </div>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
</div>

@push("scripts")
    <script src="{{ url("build.js") }}"></script>
@endpush