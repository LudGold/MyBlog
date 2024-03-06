<?php

namespace Core\Middleware;

use Pecee\SimpleRouter\SimpleRouter;

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
         /* Load external routes file */
         require_once CORE_DIR . '/Router/Routes.php';
        parent::start();
    }
}
