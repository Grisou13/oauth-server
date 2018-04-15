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
use \Illuminate\Support\Facades\Auth;
/**
 * @var $router Illuminate\Routing\Router
 */

$router->get('/', function () use ($router) {
    return view("test");
    return view("home");
});
$router->get('/logout', function () use ($router) {
    Auth::logout();
    return redirect()->to("/");
});
$router->get("/register", function() {
    return view("register");
});

$router->get("/login", function(Request $request) {
    return view("login", ["error"=>$request->get("error",false)]);
});

$router->get("/tutorial", function(){
   return view("tutorial");
});

function getCurrentUser(){
    return Auth::user();
}
$router->group(['middleware' => 'auth:web', "prefix"=>"dashboard"], function () use ($router) {
    /**
     * Root of dashboard
     */
    $router->get("/",function(Request $request){
        return view("dashboard",["authToken"=>$request->user()->token]);
    });

    $router->group(["prefix"=>"projects"], function() use($router){
        /**
         * Get the list of all apps the user can ask for access
         */
        $router->get("/apps", function(){
            $projects = App\Project::doesntHave('approvals')->notMine()->get();
            return $projects->toJson();
        });
        /**
         * Allows a user to ask for a project
         */
        $router->post("/apps/{project_id}/ask",function($project_id){
            $project = \App\Project::findOrFail($project_id);
            //check if approval already exists
            $approvalRequest = \App\Approval::where([
              "user_id"=>getCurrentUser()->id,
              "project_id" => $project->id
            ])->firstOrNew([
                "user_id"=>getCurrentUser()->id,
                "project_id" => $project->id
            ]);

            //$approvalRequest = new \App\Approval();
            if(!$approvalRequest->exists){
              $approvalRequest->user()->associate(getCurrentUser());
            }
            $approvalRequest->approved = false;
            $approvalRequest->save();
            return $approvalRequest->toJson();
        });
        /**
         * Allows a user to delete a request he has for a project
         */
        $router->delete("/apps/{project_id}/delete",function($project_id){
            $project = \App\Project::findOrFail($project_id);
            $approvalRequest = \App\Approval::whereHas("project", function($query) use ($project){
              $query->where("projects.id",$project->id);
            })->where("user_id","=",getCurrentUser()->id)->firstOrFail();

            $approvalRequest->delete();
            return response('',200);
        });
        /**
        * Approve the request
        */
        $router->post("/apps/{project_id}/approve/{user_id}",function($project_id, $user_id){
            $project = \App\Project::findOrFail($project_id);
            if($project->user->id != getCurrentUser()->id)
                return response("Not authorized, this isn't your project", 403);

            $approvalRequest = \App\Approval::where("project_id",$project_id)
                ->where("user_id",$user_id)
                ->firstOrFail();
            $approvalRequest->update(["approved"=>true]);
            return response("");
        });
        $router->post("/apps/{project_id}/revoke/{user_id}",function($project_id, $user_id){
            $project = \App\Project::findOrFail($project_id);
            if($project->user->id != getCurrentUser()->id)
                return response("Not authorized, this isn't your project", 403);

            $approvalRequest = \App\Approval::where("project_id",$project_id)
                ->where("user_id",$user_id)
                ->firstOrFail();
            $approvalRequest->update(["approved"=>false]);
            return response("");
        });
        /**
         * Get the list of all apps that the user already approved
         */
        $router->get("/approved", function(){
            $pending = \App\Approval::with(["project","user"])->where("approved",true)->whereHas("project", function($query){
                $query->where("user_id", getCurrentUser()->id);
            })->get();
            return $pending->toJson();
        });
        $router->get("/approved/{project_id}", function($project_id){
            $mine = \App\Approval::with(["project","user"])
                ->where("approved",true)
                ->whereHas("project", function($query) use($project_id) {
                    $query->where("user_id", getCurrentUser()->id);
                    $query->where("id", $project_id);
                })->get();
            return $mine->toJson();
        });
        /**
         * Get the list of all apps to be approved by the user
         */
        $router->get("/pending", function(){
            $mine = \App\Approval::with(["project","user"])
                ->where("approved",false)
                ->whereHas("project", function($query){
                    $query->where("user_id", getCurrentUser()->id);
                })->get();
            return $mine->toJson();
        });
        $router->get("/pending/{project_id}", function($project_id){
            $mine = \App\Approval::with(["project","user"])->where("approved",false)->whereHas("project", function($query) use($project_id) {
                $query->where("user_id", getCurrentUser()->id);
                $query->where("id", $project_id);
            })->get();
            return $mine->toJson();
        });

        /**
         * View all my projects
         */
        $router->get("/", function(Request $request){
            $projects = \App\Project::where("user_id","=", \Illuminate\Support\Facades\Auth::user()->id)->get();
            if($request->ajax())
                return $projects->toJson();

            return view("project.list", compact("projects"));

        });
        /**
         * View details of a project
         */
        $router->get("/{id}", function($id, Request $request){
            $project = \App\Project::findOrFail($id);
            return $project->toJson();
        });
        /**
        * Delete a project
        */
        $router->delete("/{id}", function($id, Request $request){
            $project = \App\Project::findOrFail($id);
            $project->delete();

            return '';
        });
        /**
         * Update a project
         */
        $router->put("/{id}", function($id, Request $request){
            //update the project
            $project = \App\Project::findOrFail($id);
            $project->fill($request->except("scopes"));
            //save the stuff
            $project->saveOrFail();
            if($request->ajax())
                return $project->toJson();

            return redirect()->to("/dashboard/projects/".$project->id);
        });
        /**
         * Create a project
         */
        $router->post("/", function(Request $request){
            //create the project
            $project = new \App\Project();
            $project->fill($request->except("scopes"));
            $user = \Illuminate\Support\Facades\Auth::user();
            $project->user()->associate($user);
            //save everything
            $project->saveOrFail();

            if($request->ajax())
                return $project->toJson();

            return redirect()->to("/dashboard/projects");
        });

        $router->group(["prefix"=>"{project_id}/scopes"], function() use ($router){
            /**
             * View all my scopes for a project
             */
            $router->get("/", function($project_id, Request $request){
                $project = \App\Project::findOrFail($project_id);
                $scopes = $project->scopes;
                return $scopes->toJson();
            });
            /**
             * View details of a scope
             */
            $router->get("/{id}", function($id, $project_id,  Request $request){
                $scope = \App\Scope::findOrFail($id);
                return $scope->toJson();
            });
            /**
             * Delete a scope
             */
            $router->delete("/{id}", function($id, $project_id,  Request $request){
                $scope = \App\Scope::findOrFail($id);
                $scope->delete();
                return '';
            });

            /**
             * Update a project
             */
            $router->put("/{id}", function($id, $project_id,  Request $request){
                //$project = \App\Project::findOrFail($project_id);

                //update the project
                $scope = \App\Scope::findOrFail($id);
                $scope->fill($request->except("scopes"));
                //update or create new scopes

                //save the stuff
                $scope->saveOrFail();
                if($request->ajax())
                    return $scope->toJson();

                return redirect()->to("/dashboard/projects/".$scope->project->id);
            });
            /**
             * Create a scope
             */
            $router->post("/", function($project_id, Request $request){
                $project = \App\Project::findOrFail($project_id);

                //create the project
                $scope = new \App\Scope();
                $scope->project()->associate($project);
                $scope->fill($request->except("scopes"));

                //save everything
                $scope->saveOrFail();

                if($request->ajax())
                    return $scope->toJson();

                return redirect()->to("/dashboard/projects/".$scope->project->id);
            });
        });

    });
});


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

function localLogin(){

}
function remoteLogin(){

}
/**
 * /login
 * This route will deliver you a token based on the user
 */
$router->post("/login", function(Request $request) {

  $callback_url = $request->query("callback_url",null);
    $error = null;
  //TODO this should be handled by remote api

    //check if we have a user
    $user = App\User::where("credential",$request->input("credential"))->first();
    if(!$user){
        if($request->ajax())
            return response("No user", 401);
        else
            $error = "user-not-found";
    }
    //make sure password matches
    if(!app("hash")->check($request->input("password"),$user->password)){
        if($request->ajax())
            return response("invalid password", 401);
        else
            $error="incorrect-password";
    }

    $token = createToken($user);
    if($error != null){
        $query = http_build_query([
            "callback_url"=>$callback_url,
            "error"=>$error
        ]);
        return redirect()->to("/login?".$query);
    }
    $query = http_build_query([
        "code"=>(string) $token,
        "credential"=>$user->credential,
        "callback_url"=>$callback_url
    ]);

    return redirect()->to(url("/login/callback")."?".$query);
});
$router->get("/me",["middleware"=>"auth:",function(Request $request){
    //todo request the profile of the user based on token to the actual profile api
    //we should get the token of the user
    return \Auth::user();
}]);
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
$router->group(["middleware"=>"auth:web"],function() use ($router){
    $router->get("/oauth/authorize", [
        'uses' => 'AuthorizationController@authorize',
    ]);
    $router->post('oauth/authorize', [
        'uses' => 'ApproveAuthorizationController@approve',
    ]);
});
