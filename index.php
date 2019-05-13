<?php
/*
 * Author: Evan Wheeler
 * Date: 4/14/19
 * Purpose: To practice using F3 and utilize the MVC design pattern (somewhat)
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

// start up f3
require_once('vendor/autoload.php');

// start up a session
session_start();

// create an instance and enable debugging
$f3 = Base::instance();
$f3->set('DEBUG', 3);


// root route, shows the landing page
$f3->route('GET /', function() {
    $view = new Template();
    echo $view->render('views/home.html');
});

// 1st of three forms
$f3->route('GET|POST /join', function() {
    $view = new Template();
    echo $view->render('views/form1.html');
});

// 2nd of three forms, collects data from form 1 in session
$f3->route('GET|POST /profile', function($f3) {
    if (validName($_POST['firstName']))
    {
        $_SESSION['firstName'] = $_POST['firstName'];
    }
    else
    {
        $errors['firstName'] = true;
    }

    if (validName($_POST['lastName']))
    {
        $_SESSION['lastName'] = $_POST['lastName'];
    }
    else
    {
        $errors['lastName'] = true;
    }

    if (validAge($_POST['age']))
    {
        $_SESSION['age'] = $_POST['age'];
    }
    else
    {
        $errors['age'] = true;
    }

    if (validPhone($_POST['phone']))
    {
        $_SESSION['phone'] = $_POST['phone'];
    }
    else
    {
        $errors['phone'] = true;
    }

    $_SESSION['gender'] = $_POST['gender'];

    if (empty($errors))
    {
        $view = new Template();
        echo $view->render('views/form2.html');
    }

    else {
        $f3->reroute('/join');
    }
});
// 3rd of three forms, collects data from form 2 in session
$f3->route('POST /interests', function() {
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['state'] = $_POST['state'];
    $_SESSION['seeking'] = $_POST['seeking'];
    $_SESSION['bio'] = $_POST['bio'];

    $view = new Template();
    echo $view->render('views/form3.html');
});

// final page, combines interests and concatenates elements to a string
$f3->route('POST /summary', function() {

    $indoor = isset($_POST['indoor']) ? $_POST['indoor'] : [];
    $outdoor = isset($_POST['outdoor']) ? $_POST['outdoor'] : [];

    $interests = "";
    foreach (array_merge($indoor, $outdoor) as $interest)
    {
        $interests .= $interest . " ";
    }
    $_SESSION['interests'] = $interests;

    $view = new Template();
    echo $view->render('views/summary.html');
});

// run f3
$f3 -> run();