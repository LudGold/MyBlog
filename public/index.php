<?php

use App\Controller\HomeController;

require_once "../vendor/autoload.php";

define('TEMPLATE_DIR', realpath(dirname(__DIR__)).'/template');

$url=$_SERVER["REQUEST_URI"];
var_dump($url);

if($url=== "/") {
  $home= new HomeController();
  $home->home();
}