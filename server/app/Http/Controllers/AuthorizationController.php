<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\User;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Contracts\Routing\ResponseFactory;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;

use League\OAuth2\Server\Exception\OAuthServerException;
class AuthorizationController
{
    use HandlesOAuthErrors;
    /**
     * The authorization server.
     *
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;
    /**
     * The response factory implementation.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;
    /**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer  $server
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @return void
     */
    public function __construct(AuthorizationServer $server, ResponseFactory $response)
    {
        $this->server = $server;
        $this->response = $response;
    }
    /**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $psrRequest
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response
     */
    public function authorize(ServerRequestInterface $psrRequest,
                              Request $request,
                              ClientRepository $clients,
                              TokenRepository $tokens)
    {
        return $this->withErrorHandling(function () use ($psrRequest, $request, $clients, $tokens) {
            try{

                $authRequest = $this->server->validateAuthorizationRequest($psrRequest);
                $scopes = $this->parseScopes($authRequest);
                $client = $clients->find($authRequest->getClient()->getIdentifier());
                $user = $request->user();
                //check if client has access to scopes
                $projectNames = collect($scopes)->pluck('id')->map(function($scopeId){
                    return explode(".",$scopeId)[0];
                })->all();
                //get all the project the users has pending approval
                // $projects = Project::whereIn("name",$projectNames)->whereHas("approvals",function($query){
                //     return $query->where("approved",true);
                // });
                // //remove scopes
                // $projects->get()->each(function($project){
                //     $scopes = $project->scopes;
                // });
                // all the scopes the user has access to
                $scopesAccessible = Scope::whereHas("project",function($query){
                  return $query->whereIn("name", $projectNames)->whereHas("approvals",function($query){
                      return $query->where("approved",true)->where("user_id",$client->user_id);
                  });
                })->get("name"); //->whereIn("name",collect($scopes)->pluck('id')->all())

                // all the scopes the user has created
                $myScopes = Scope::whereHas("project",function($query){
                  return $query->where("user_id",$client->user_id);
                })->get("name");

                $scopesAccessible = $scopesAccessible->merge($myScopes);

                $scopes = collect($scopes)->filter(function($s){
                  return $scopesAccessible->contains($s["id"]);
                });
                //dd($scopes,$scopesAccessible);
                // input scopes
                // [
                // [ "scope.name" => "Scope description"]
                //]

                // from db
                // [
                // [ "scope.name" => "Scope description"],
                // [ "scope.name" => "Scope description"]
                //]

//                if($projects->count())
//                    throw new OAuthServerException("The client {$client->id} isn't allowed to access the following scopes : []",401,"client-not-allowed-scope");
                //actually give him a token
                $token = $tokens->findValidToken(
                    $user,
                    $client
                );

                if ($token && $token->scopes === collect($scopes)->pluck('id')->all()) {
                    return $this->approveRequest($authRequest, $user);
                }
                $request->session()->put('authRequest', $authRequest);
                return $this->response->view('authorize', [
                    'client' => $client,
                    'user' => $user,
                    'scopes' => $scopes,
                    'request' => $request,
                ]);
            }catch(OAuthServerException $e){
                throw $e;
//                dd($e);
                /*if($e->getErrorType() == "invalid_client" && $e->getHttpStatusCode() == 401){ //user needs to login
                    return redirect()->to("/login?callback_url=".urlencode($request->fullUrl()));
                }
                else
                    throw $e;*/
            }
        });
    }
    /**
     * Transform the authorization requests's scopes into Scope instances.
     *
     * @param  \League\OAuth2\Server\RequestTypes\AuthorizationRequest  $authRequest
     * @return array
     */
    protected function parseScopes($authRequest)
    {

        return Passport::scopesFor(
            collect($authRequest->getScopes())->map(function ($scope) {
                return $scope->getIdentifier();
            })->all()
        );
    }
    /**
     * Approve the authorization request.
     *
     * @param  \League\OAuth2\Server\RequestTypes\AuthorizationRequest  $authRequest
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @return \Illuminate\Http\Response
     */
    protected function approveRequest($authRequest, $user)
    {
        $authRequest->setUser(new User($user->getKey()));
        $authRequest->setAuthorizationApproved(true);
        return $this->convertResponse(
            $this->server->completeAuthorizationRequest($authRequest, new Psr7Response)
        );
    }
}
