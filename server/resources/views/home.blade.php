@extends("layout")

@section("content")
<div id="app">
    <h1>Hello App!</h1>
    <p>
    <!-- use router-link component for navigation. -->
    <!-- specify the link by passing the `to` prop. -->
    <!-- `<router-link>` will be rendered as an `<a>` tag by default -->
    <router-link to="/">Go to /</router-link>
    <router-link to="/clients">Go to Clients</router-link>
    </p>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
</div>
<script src="/build.js"></script>
@endsection