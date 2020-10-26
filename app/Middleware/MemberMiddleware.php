<?php

//region Namespace
namespace App\Middleware;
//endregion

class MemberMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->member->check()) {
            $this->container->flash->addMessage('error', 'Giriş yapmanız gerekmektedir.');
            return $response->withRedirect($this->container->router->pathFor('member-signin'));
        }

        $response = $next($request, $response);
        return $response;
    }
}