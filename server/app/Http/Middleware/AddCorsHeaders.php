<?php
namespace App\Http\Middleware;
use Closure;
class AddCorsHeaders
{
    protected $headers =  [
        "Access-Control-Allow-Origin"=>"http://localhost:8081",
        "Access-Control-Allow-Headers" => "X-Requested-With, Content-Type, X-Access-Token, x-access-token, Authorization, api_key, x-xsrf-token",
        // "Access-Control-Allow-Headers" => "True",
        "Access-Control-Request-Method"=>"GET, POST, PUT, PATCH, DELETE, OPTIONS",
        "Access-Control-Allow-Methods"=>"GET, POST, PUT, PATCH, DELETE, OPTIONS",
        "Access-Control-Request-Headers"=>"X-Requested-With, Content-Type, X-Access-Token, x-access-token, Authorization, api_key, x-xsrf-token",
        "Access-Control-Max-Age"=>0,
        "Access-Control-Allow-Credentials"=>'true'
    ];
    public function handle($request, Closure $next)
    {

        if ($request->getMethod() === "OPTIONS") {
            return response('',200,$this->headers);
        }
        /**
         * @var $response \Illuminate\Http\Response
         */
        $response = $next($request);
        if(config("app.env") === "production"){

            $this->headers["Access-Control-Allow-Origin"] = "*";
        }
        $this->attachHeaders($response);

        return $response;
    }
    protected function attachHeaders($response){
        $IlluminateResponse = 'Illuminate\Http\Response';
        $SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
        if($response instanceof $IlluminateResponse) {
            foreach ($this->headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        if($response instanceof $SymfonyResopnse) {
            foreach ($this->headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }
    }
}