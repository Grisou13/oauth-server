<div id="app" class="container">
    <h1>Welcome to your dashboard</h1>
    <div class="row">
        <router-link tag="button" to="/clients"><a href="#" class="btn col s12 m6"> Manage your api clients</a></router-link>
        <router-link tag="button" to="/projects"><a href="#" class="btn col s12 m6"> Manage your apis</a></router-link>
    </div>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
</div>

@push("scripts")
<script type="text/javascript">
  $(document).ready(function(){
   $('.tabs').tabs();
  });
</script>
<script src="{{ url("build.js") }}"></script>

@endpush
