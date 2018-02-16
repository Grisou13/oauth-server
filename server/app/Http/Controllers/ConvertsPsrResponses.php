<?php
/**
 * Created by PhpStorm.
 * User: Thomas.RICCI
 * Date: 09.02.2018
 * Time: 12:04
 */

namespace App\Http\Controllers;


use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;
trait ConvertsPsrResponses
{
    /**
     * Convert a PSR7 response to a Illuminate Response.
     *
     * @param \Psr\Http\Message\ResponseInterface $psrResponse
     * @return \Illuminate\Http\Response
     */
    public function convertResponse($psrResponse)
    {
        return new Response(
            $psrResponse->getBody(),
            $psrResponse->getStatusCode(),
            $psrResponse->getHeaders()
        );
    }
}