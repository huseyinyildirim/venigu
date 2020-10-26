<?php

//region Namespace
namespace App\Middleware;
//endregion

class ManagementMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->management->check()) {
            $this->container->flash->addMessage('error', 'Giriş yapmanız gerekmektedir.');
            return $response->withRedirect($this->container->router->pathFor('management-login'));
        }

        $response = $next($request, $response);
        return $response;
    }
}