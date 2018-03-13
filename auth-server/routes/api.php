<?php

use Illuminate\Http\Request;
use Lcobucci\JWT\Builder;
use App\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/login",function(Request $request){
    if(!Auth::attempt($request->get(["email","password"])))
        return response()->status(301);

    $signer = new Sha256();
    $token = (new Builder())->setIssuer(url()) // Configures the issuer (iss claim)
        ->setAudience(url()) // Configures the audience (aud claim)
        ->setId(str_random(16), true) // Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
        ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
        ->set('uid', Auth::id()) // Configures a new claim, called "uid"
        ->sign($signer, config("app.key")) // creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token
        
    Auth::logout();
    return (string) $token;
});