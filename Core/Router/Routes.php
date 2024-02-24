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
Router::all('/admin/adminDashboard', "Admin\UserAdminController@adminDashboard")->setName("tableau_de_bord");
Router::all('/admin/user/addRole', "Admin\UserAdminController@updateUserRole")->setName("attribution_role");
Router::all('/forgotPassword', "UserController@forgotPassword")->setName("forgotPassword");
Router::all('/resetPassword/{resetToken}', "UserController@resetPassword")->setName("resetPassword");
Router::all('/login', "UserController@loginUser")->setName("login");
Router::post('/contact', "ContactController@contact")->setName("contact");
Router::all('/deconnexion', "UserController@logout")->setName("logout");
Router::get('/mentionsLegales', "LegalController@mentionsLegales")->setName("mentions_legales");
//where permet de gérer les regex des suites d'url
Router::get('/test/{id}', "HomeController@param")->where(['id' => '[0-9]+'])
    ->setName("test");
Router::all('/contact', "ContactController@contact")->setName("contact");
Router::get('/not-found', 'ErrorController@notFound');
Router::get('/not-authorised', 'ErrorController@notAuthorised');

Router::error(function (Request $request, \Exception $exception) {
    switch($exception->getCode()) {
        // Page not found
        case 404:
            Router::redirect('/not-found', 404);
            break;
        // Forbidden
        case 403:
            Router::redirect('/not-authorised', 403);
            break;
        // other error
        default: 
            Router::redirect('/not-found', 404);
            break;
    }
});

