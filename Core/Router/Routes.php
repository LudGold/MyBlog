<?php

use Core\Middleware\Router;

Router::setDefaultNameSpace("App\Controller");

Router::get('/', "HomeController@home")->setName("home");
//where permet de gérer les regex des suites d'url
Router::get('/test/{id}', "HomeController@param")->where([ 'id' => '[0-9]+' ])
->setName("test");
