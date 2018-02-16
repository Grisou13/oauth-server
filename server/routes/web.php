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

$router->get('/', function () use ($router) {
    return view("home");
});
$router->get("/register", function() {

});
$router->get("/login", function() {

});
$router->post("/register", function() {

});
$router->post("/login", function() {
  // todo redirect to login plateform with request
});
// TODO define a scope for this route
$router->get("/profile",function(){

});

$router->get("/login/callback",function(Illuminate\Http\Request $request){
  $access_token = $request->get("token");
  // get the user from access token
  $client = new \GuzzleHttp\Client();
  $res = $client->request('GET', 'https://api.cpnv-es.ch/me?access_token='.$client);
  $user = App\User::findOrNew($res["id"]);
  $user->update($res);
  $user->save();
  auth()->loginWithId($user->id);
  
});
/*
 * https://YOUR_AUTH0_DOMAIN/authorize?
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
// check scopes
$router->get("/oauth/scopes/check",function(){

});