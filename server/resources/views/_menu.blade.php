<ul id="tutorial-drop-menu" class="dropdown-content">
    <li><a href="{{ url("/tutorial") }}">Getting started</a></li>
    <li><a href="{{ url("/tutorial#integration") }}">Integrating to an existing app</a></li>
    <li class="divider"></li>
    <li><a href="{{ url("/tutorial#create-your-app") }}">Creating your own third party app</a></li>
</ul>
<nav>
    <div class="nav-wrapper white ">
        <a href="#" class="brand-logo black-text">CPNV oauth server</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a class="black-text" href="/api">Api</a></li>
            <li><a class="black-text" href="{{ url("/login") }}">Login</a></li>
            <li><a class="black-text" href="{{ url("/register") }}">Register</a></li>
            <ul class="right hide-on-med-and-down">
                <!-- Dropdown Trigger -->
                <li><a class="dropdown-trigger black-text" href="#!" data-target="tutorial-drop-menu" >Tutorial<i class="material-icons right black-text">arrow_drop_down</i></a></li>
            </ul>
        </ul>
    </div>
</nav>

<ul class="sidenav" id="mobile-demo">
    <li><a href="{{ url("/login") }}">Login</a></li>
    <li><a href="{{ url("/register") }}">Register</a></li>
    <li><a href="{{ url("/tutorial") }}">Getting started</a></li>

</ul>