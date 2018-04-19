<nav>
    <div class="nav-wrapper white ">
        <a href="#" class="brand-logo black-text">CPNV oauth server</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a class="black-text" href="/">Auth server</a></li>
            @if(Auth::check())
            <li><a class="black-text" href="{{ url("/logout") }}">Logout</a></li>
            @else
            <li><a class="black-text" href="{{ url("/login") }}">Login</a></li>
            <li><a class="black-text" href="{{ url("/register") }}">Register</a></li>
            @endif

            <ul class="right hide-on-med-and-down">
                <!-- Dropdown Trigger -->
                <li><a class="dropdown-trigger black-text" href="#!" data-target="tutorial-drop-menu" >Tutorial<i class="material-icons right black-text">arrow_drop_down</i></a></li>
            </ul>
        </ul>
    </div>
</nav>
