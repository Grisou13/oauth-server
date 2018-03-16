<?php

namespace CPNVES\Auth\Client;

class Client{
    protected $provider;
    protected $domain = "http://localhost:8000";
    public function __construct($clientId, $clientSecret, $redirectUri){
        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $clientId,    // The client ID assigned to you by the provider
            'clientSecret'            => $clientSecret,    // The client password assigned to you by the provider
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $this->domain.'/oauth/authorize',
            'urlAccessToken'          => $this->domain.'/oauth/token',
            'urlResourceOwnerDetails' => $this->domain.'/api/profile'
        ]);
        
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

