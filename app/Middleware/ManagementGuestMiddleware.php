<?php

//region Namespace
namespace App\Middleware;
//endregion

class ManagementGuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ($this->container->management->check()) {
            return $response->withRedirect($this->container->router->pathFor('management-home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}