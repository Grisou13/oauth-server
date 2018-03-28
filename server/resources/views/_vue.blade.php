<div id="app" class="container">
    <div class="row">
        <button type="button" class="btn col s12 m6"><router-link tag="span" to="/clients">Manage your api clients</router-link></button>
        <button type="button" class="btn col s12 m6"><router-link tag="span" to="/projects">Manage your apis</router-link></button>
    </div>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <transition name="fade">
        <router-view></router-view>
    </transition>
</div>

@push("scripts")
<script type="text/javascript">
  $(document).ready(function(){
   //$('.tabs').tabs();
  });
</script>
<script src="{{ url("build.js") }}"></script>

@endpush
