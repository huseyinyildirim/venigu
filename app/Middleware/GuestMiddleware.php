<?php

//region Namespace
namespace App\Middleware;
//endregion

class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ($this->container->member->check()) {
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}