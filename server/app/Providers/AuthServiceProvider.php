<?php

namespace App\Providers;

use App\Auth\Guard\TokenAuthGuard;
use App\Scope;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      try{
        $scopes = Scope::all()->pluck("description","name")->all();

        Passport::tokensCan($scopes);
      }catch(\Exception $e){}

    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {


        $this->app['auth']->extend('token', function($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return new TokenAuthGuard(Auth::createUserProvider($config['provider']), $this->app["request"]);
        });
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('web', function ($request) {
//            dd("TAMER");
            if(array_key_exists("token",$_COOKIE)){
                return User::where("token",$_COOKIE["token"])->first();
            }
            if ($request->header('Authorization')) {
                
                $response = explode(' ', $request->header('Authorization'));
                $token = trim($response[1]);
                return User::where('token', $token)->first();
            }

        });
        $this->app['auth']->viaRequest('api', function ($request) {

            if(array_key_exists("token",$_COOKIE)){
                return User::where("token",$_COOKIE["token"])->first();
            }
            if ($request->header('Authorization')) {

                $response = explode(' ', $request->header('Authorization'));
                $token = trim($response[1]);
                return User::where('token', $token)->first();
            }
        });
    }
}
