<div id="app">
    <h1>Hello App!</h1>
    <p>
        <!-- use router-link component for navigation. -->
        <!-- specify the link by passing the `to` prop. -->
        <!-- `<router-link>` will be rendered as an `<a>` tag by default -->
        <!-- <router-link to="/">Go to /</router-link> -->
    </p>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
</div>

@push("scripts")
    <script src="{{ url("build.js") }}"></script>
@endpush