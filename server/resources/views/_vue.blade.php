<div id="app" class="container">
    <h1>Welcome to your dashboard</h1>
    <div class="row">
        <h2>Manage your apis</h2>
        <router-link tag="button" to="/clients"><a href="#" class="btn col s12"> Manager your api clients</a></router-link>
    </div>
    <div class="row">
        <h2>Manage your api clients</h2>
        <router-link tag="button" to="/clients"><a href="#" class="btn col s12"> Manager your apis</a></router-link>
    </div>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
</div>

@push("scripts")
    <script src="{{ url("build.js") }}"></script>
@endpush