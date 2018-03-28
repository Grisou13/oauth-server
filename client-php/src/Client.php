<?php

namespace CPNVES\Auth\Client;

class Client{
    protected $provider;
    
    const domain = "http://localhost:8000";

    public function __construct($clientId, $clientSecret, $redirectUri){
        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $clientId,    // The client ID assigned to you by the provider
            'clientSecret'            => $clientSecret,    // The client password assigned to you by the provider
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $this->getAuthorizationDomain().'/oauth/authorize',
            'urlAccessToken'          => $this->getAuthorizationDomain().'/oauth/token',
            'urlResourceOwnerDetails' => $this->getAuthorizationDomain().'/api/profile',
            "scopeSeparator"          => " "
        ]);
        
    }
    public function getAuthorizationDomain(){
        return constant("self::domain");
    }
    /**
     * Magically call the JWT instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->provider, $method)) {
            return call_user_func_array([$this->provider, $method], $parameters);
        }
        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}

