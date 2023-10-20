<?php

use Core\Middleware\Router;
use Pecee\Http\Request;

Router::setDefaultNameSpace("App\Controller");

Router::all('/', "HomeController@home")->setName("home");
Router::all('/articles', "ArticleController@article")->setName("articles");
Router::all('/article', "ArticleController@show")->setName("article");
Router::all('/register', "UserController@registerUser")->setName("register");
//where permet de gérer les regex des suites d'url
Router::get('/test/{id}', "HomeController@param")->where([ 'id' => '[0-9]+' ])
->setName("test");
Router::get('/not-found', 'ErrorController@notFound');
Router::get('/not-authorised', 'ErrorController@notAuthorised');

Router::error(function(Request $request,\Exception $exception) {

    // switch($exception->getCode()) {
    //     // Page not found
    //     case 404:
    //         response()->redirect('/not-found');
    //         break;
    //     // Forbidden
    //     case 403:
    //         response()->redirect('/not-authorised');
    //         break;
    //     // other error
    //     default: 
    //         response()->redirect('/not-found');
    //         break;
    // }
    
});
