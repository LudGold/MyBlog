<?php

namespace Core\Middleware;

use Pecee\SimpleRouter\SimpleRouter;
use Core\Router\Routes;

class Router extends SimpleRouter
{
    /**
     * @throws \Exception
     * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
     * @throws \Pecee\SimpleRouter\Exceptions\HttpException
     * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
     */
    public static function start(): void
    {

        Routes::loadRoutes();
        parent::start();
    }
}
