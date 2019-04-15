<?php
/*
 * Author: Evan Wheeler
 * Date: 4/14/19
 * Purpose: To practice using F3 and utilize the MVC design pattern (somewhat)
 */
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