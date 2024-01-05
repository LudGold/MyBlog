<?php

use Core\Middleware\Router;
use Pecee\Http\Request;

Router::setDefaultNameSpace("App\Controller");


Router::all('/', "HomeController@home")->setName("home");
Router::all('/admin/articles', "Admin\ArticleAdminController@index")->setName("admin_articles");
Router::all('/admin/newArticle', "Admin\ArticleAdminController@newArticle")->setName("admin_newArticle");
Router::all('/admin/deleteArticle/{articleId}', "Admin\ArticleAdminController@deleteArticle")->setName("supp-articles");
Router::all('/article', "ArticleController@show")->setName("article");
Router::all('/register', "UserController@registerUser")->setName("register");
Router::get('/confirmation/{token}', "UserController@confirmEmail")->setName("confirmation");
Router::all('/forgotPassword', "UserController@forgotPassword")->setName("forgotPassword");
Router::all('/resetPassword/{resetToken}', "UserController@resetPassword")->setName("resetPassword");
Router::all('/login', "UserController@loginUser")->setName("login");
Router::all('/deconnexion', "UserController@logout")->setName("logout");  
//where permet de gérer les regex des suites d'url
Router::get('/test/{id}', "HomeController@param")->where(['id' => '[0-9]+'])
    ->setName("test");
Router::all('/contact', "ContactController@contact")->setName("contact"); 
Router::get('/not-found', 'ErrorController@notFound');
Router::get('/not-authorised', 'ErrorController@notAuthorised');

Router::error(function (Request $request, \Exception $exception) {

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
