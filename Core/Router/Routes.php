<?php

use Core\Middleware\Router;
use Pecee\Http\Request;

Router::setDefaultNameSpace("App\Controller");


Router::all('/', "HomeController@home")->setName("home");
Router::all('/admin/articles', "Admin\ArticleAdminController@index")->setName("admin_articles");
Router::all('/admin/newArticle', "Admin\ArticleAdminController@newArticle")->setName("admin_newArticle");
Router::all('/admin/changeArticle/{articleId}', "Admin\ArticleAdminController@changeArticle")->setName("admin_changeArticle");
Router::all('/admin/deleteArticle/{articleId}', "Admin\ArticleAdminController@deleteArticle")->setName("supp_articles");
Router::get('/admin/comment/comments_pending', "Admin\CommentsAdminController@showPendingComments")->setName("admin_pending_comments");
Router::get('/admin/comment/comments_approved', "Admin\CommentsAdminController@showApprovedComments")->setName("admin_approved_comments");
Router::get('/admin/comment/comments_rejected', "Admin\CommentsAdminController@showRejectedComments")->setName("admin_rejected_comments");
Router::post('/admin/check-comment', "Admin\CommentsAdminController@checkedComment")->setName("check_comment");
Router::get('/admin/comment/index', "Admin\CommentsAdminController@showAllComments")->setName("admin_allComments");
Router::post('/article/{articleId}/submitComment', "ArticleController@submitComment")->setName("submitComment");
Router::all('/admin/check-comment/reject', "Admin\CommentsAdminController@rejectedComments")->setName("rejectComment");
Router::get('/articles', "ArticleController@displayAllArticles")->setName("articles");
Router::all('/article/{articleId}', "ArticleController@show")->setName("article_show");
Router::all('/register', "UserController@registerUser")->setName("register");
Router::get('/confirmation/{token}', "UserController@confirmEmail")->setName("confirmation");
Router::all('/editProfil', "UserController@editProfil")->setName("profil_modifié");
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
