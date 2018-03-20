<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use \Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return view("home");
});
$router->get("/register", function() {
    return view("register");
});
$router->get("/login", function() {
    return view("login");
});
$router->get("/dashboard", ['middleware' => 'auth',function(Request $request){
    return view("dashboard",["authToken"=>$request->user()->token]);
}]);

function createToken($user){

    $signer = new Sha256();
    return (new Builder())->setIssuer(url()) // Configures the issuer (iss claim)
        ->setAudience(url()) // Configures the audience (aud claim)
        ->setId(config("app.key"), true) // Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
        ->setNotBefore(time() + 3600*24*30) // Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600*24*30) // Configures the expiration time of the token (exp claim)
        ->set('uid', $user->id) // Configures a new claim, called "uid"
        ->sign($signer, config("app.key")) // creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token
}
$router->get("/logout",function(Request $request){
    $callback_url = $request->get("callback_url",url("/login"));
    setcookie("token", "", time()-3600);
    return redirect()->to($callback_url);
});
$router->post("/register", function(Request $request) {
    $callback_url = $request->get("callback_url",url("/dashboard"));

    //create a user
    $user = new App\User(["credential"=>$request->input(["credential"])]);
    $user->password = app('hash')->make($request->input("password"));
    $user->save();
    $user->token = createToken($user);
    $user->save();
    $query = http_build_query([
        "code"=>(string) $user->token,
        "credential"=>$user->credential,
        "callback_url"=>$callback_url
    ]);
    return redirect()->to("/login/callback?".$query);
});
/**
 * /login
 * This route will deliver you a token based on the user
 */
$router->post("/login", function(Request $request) {

  $callback_url = $request->query("callback_url",null);
  //get a token for the user
  //this should be handled by remote api
    $user = App\User::where("credential",$request->input("credential"))->first();
    if(!$user)
        return response("No user", 401);
    if(!app("hash")->check($request->input("password"),$user->password))
        return response("invalid password", 401);

    $token = createToken($user);

    $query = http_build_query([
        "code"=>(string) $token,
        "credential"=>$user->credential,
        "callback_url"=>$callback_url
        ]);

    return redirect()->to(url("/login/callback")."?".$query);
});

// TODO define a scope for this route
$router->get("/api/profile",["middleware"=>"auth:api",function(Request $request){
    //todo request the profile of the user based on token to the actual profile api
    //we should get the token of the user
    return \Auth::guard('api')->user();
}]);
/**
 * /login/callback
 * Registers the user credentials
 */
$router->get("/login/callback",function(Request $request){
  $callback_url = $request->get("callback_url",url("/dashboard"));
  $access_token = $request->input("code");
  $username = $request->input("credential");
  // get the user from access token
  /*$client = new \GuzzleHttp\Client();
  $res = $client->request('GET', 'https://api.cpnv-es.ch/me?access_token='.$access_token);*/
  $user = App\User::where("credential",$username)->first();
  $user->token = $access_token;
  $user->save();
  setcookie("token", $access_token,time() + (86400 * 30), "/");



    if($request->ajax())
    return $access_token;
    //dd(url($callback_url));

  return redirect()->to($callback_url);

});
/*
 * https://here/authorize?
 audience=YOUR_API_AUDIENCE&
scope=openid%20profile%20email%20read:contacts&
response_type=id_token%20token&
client_id=YOUR_CLIENT_ID&
redirect_uri=https://YOUR_APP/callback&
  nonce=YOUR_CRYPTOGRAPHIC_NONCE
  state=YOUR_OPAQUE_VALUE
*/

/*
 https://YOUR_AUTH0_DOMAIN/authorize?
  audience=api.calendar&
  scope=read write&
  response_type=token&
  client_id={account.clientId}&
  redirect_uri=https://YOUR_APP/callback&
  nonce={CRYPTOGRAPHIC_NONCE}
  state={OPAQUE_VALUE}
 */
// this page will prompty the user and ask him if he authorizes this client_id and token to access scopes

// audience should be the api name, defined in env vars
// scope should be the scopes wanted for this token

// issue a token with information encoded information:
//
$router->get("/oauth/authorize", [
  'uses' => 'AuthorizationController@authorize',
]);
$router->post('oauth/authorize', [
  'uses' => 'ApproveAuthorizationController@approve',
]);
