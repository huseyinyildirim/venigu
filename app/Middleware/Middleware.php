<?php

//region Namespace
namespace App\Middleware;
//endregion

class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}