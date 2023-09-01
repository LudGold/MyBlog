<?php

use App\Controller\HomeController;

require_once "../vendor/autoload.php";

define('TEMPLATE_DIR', realpath(dirname(__DIR__)).'/template');
define('CORE_DIR', realpath(dirname(__DIR__)).'/Core');

$url=$_SERVER["REQUEST_URI"];

\Core\Middleware\Router::start();

// if($url=== "/") {
//   $home= new HomeController();
//   $home->home();
// }