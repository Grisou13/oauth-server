<?php
/**
 * Created by PhpStorm.
 * User: Thomas.RICCI
 * Date: 16.03.2018
 * Time: 08:17
 */

namespace App\Auth\Guard;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;

class TokenAuthGuard implements Guard
{
    use GuardHelpers;
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new authentication guard.
     *
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Illuminate\Http\Request                $request
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        $token = $this->request->cookie("token");
        if(!$token){
          $token = $this->request->header("Authorization");
          if(!empty($token))
            $token = explode(" ",$token)[1];
          else
            return null;
        }

        return $this->getUserFromToken($token);

    }
    protected function getUserFromToken(string $token){
      try{
        $token = (new Parser())->parse($token);
        $id = $token->getClaim('uid');
        return $this->user = $this->provider->retrieveById($id);
      }catch(\Exception $e){
        //TODO implement own provider
        return $this->user = \App\User::where("remember_token",$token)->orWhere("token",$token)->first(); //just hard code this
      }

    }


    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }
    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function once(array $credentials = [])
    {
        if ($this->validate($credentials)) {
            $this->setUser($this->lastAttempted);
            return true;
        }
        return false;
    }
    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

}
