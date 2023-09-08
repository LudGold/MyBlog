<?php

use Core\Middleware\Router;

Router::setDefaultNameSpace("App\Controller");

Router::all('/', "HomeController@home")->setName("home");
Router::all('/articles', "ArticlesController@home")->setName("articles");
//where permet de gérer les regex des suites d'url
Router::get('/test/{id}', "HomeController@param")->where([ 'id' => '[0-9]+' ])
->setName("test");

