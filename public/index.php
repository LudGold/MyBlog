<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Core\Middleware\Router;
use App\Controller\HomeController;

require_once "../vendor/autoload.php";

define('TEMPLATE_DIR', realpath(dirname(__DIR__)).'/template');
define('CORE_DIR', realpath(dirname(__DIR__)).'/Core');

$loader = new FilesystemLoader(TEMPLATE_DIR);
$twig = new Environment($loader);

$url=$_SERVER["REQUEST_URI"];
session_start();
\Core\Middleware\Router::start();

