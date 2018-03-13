<?php
namespace App\Http\Middleware;
use Closure;
class AddCorsHeaders
{
    protected $headers =  [
        "Access-Control-Allow-Origin"=>"*",
        "Access-Control-Allow-Headers" => "X-Requested-With, Content-Type, X-Access-Token, x-access-token, Authorization, api_key, x-xsrf-token",
        // "Access-Control-Allow-Headers" => "True",
        "Access-Control-Request-Method"=>"GET, POST, PUT, PATCH, DELETE, OPTIONS",
        "Access-Control-Allow-Methods"=>"GET, POST, PUT, PATCH, DELETE, OPTIONS",
        "Access-Control-Request-Headers"=>"X-Requested-With, Content-Type, X-Access-Token, x-access-token, Authorization, api_key, x-xsrf-token",
        "Access-Control-Max-Age"=>0,
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
        // Perform action
        $response->withHeaders($this->headers);

        return $response;
    }
}