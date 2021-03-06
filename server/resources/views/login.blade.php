@extends("layout")

@section("content")
    <div class="container">
        <div class="row">
            <form class="show-password log-in-form" method="post" action="/login?{{ \Illuminate\Support\Facades\Request::getQueryString() }}">
                <div class="col s12">
                    <div class="input-field col s12">
                        <label for="credential">Username / Email</label>
                        <input type="text" name="credential" id="username" placeholder="Username / Email" class="{{ $error == "user-not-found" ? "invalid" : "" }}">
                    </div>
                    <div class="input-field col s12">

                        <div class="password-wrapper">
                            <label for="passowrd">Password</label>
                            <input type="password" name="password" value="" placeholder="Enter Password" id="password" class="password {{ $error == "incorrect-password" ? "invalid" : "" }}">
                            <button class="unmask" type="button" title="Mask/Unmask password to check content">Unmask</button>
                        </div>
                        <label for="password">Password</label>

                    </div>

                    <button type="submit" class="col s12 waves-effect waves-light btn light-blue darken-1"><span>Log-in<i class="material-icons right">send</i></span></button>

                </div>

            </form>
        </div>

        <div class="row">
            <p>You don't have an acocunt? We'll then just <a href="/register?{{ \Illuminate\Support\Facades\Request::getQueryString() }}" class="waves-effect waves-light btn cyan lighten-3">Register</a> one</p>
        </div>
    </div>
@endsection
