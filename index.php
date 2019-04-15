<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('vendor/autoload.php');

$f3 = Base::instance();
$f3->set('DEBUG', 3);

$f3->route('GET /', function(){
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3 -> run();