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
// load validation functions
require_once('model/validation.php');

// start up a session
session_start();

// create an instance and enable debugging
$f3 = Base::instance();
$f3->set('DEBUG', 3);

// root route, shows the landing page
$f3->route('GET /', function()
{
    $view = new Template();
    echo $view->render('views/home.html');
});

// 1st of three forms
$f3->route('GET|POST /join', function($f3)
{
    $errors = array();
    $_SESSION = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (validName($_POST['firstName'])) {
            $_SESSION['firstName'] = $_POST['firstName'];
        } else {
            $errors[] = 'firstName';
        }

        if (validName($_POST['lastName'])) {
            $_SESSION['lastName'] = $_POST['lastName'];
        } else {
            $errors[] = 'lastName';
        }

        if (validAge($_POST['age'])) {
            $_SESSION['age'] = $_POST['age'];
        } else {
            $errors[] = 'age';
        }

        if (validPhone($_POST['phone'])) {
            $_SESSION['phone'] = $_POST['phone'];
        } else {
            $errors[] = 'phone';
        }

        $_SESSION['gender'] = $_POST['gender'];

        if (empty($errors)) {
            $f3->reroute('profile');
        }
    }

    $f3->set('errors', $errors);
    $view = new Template();
    echo $view->render('views/form1.html');
});

// 2nd of three forms, collects data from form 1 in session
$f3->route('GET|POST /profile', function($f3)
{
    $errors = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (validEmail($_POST['email'])) {
            $_SESSION['email'] = $_POST['email'];
        } else {
            $errors[] = 'email';
        }

        $_SESSION['state'] = $_POST['state'];
        $_SESSION['seeking'] = $_POST['seeking'];
        $_SESSION['bio'] = $_POST['bio'];

        if (empty($errors))
        {
            $f3->reroute('interests');
        }
    }

    $f3->set('errors', $errors);
    $view = new Template();
    echo $view->render('views/form2.html');
});

// 3rd of three forms, collects data from form 2 in session
$f3->route('GET|POST /interests', function($f3) {

    $errors = array();
    // refresh just the data on this page
    $_SESSION['indoor'] = array();
    $_SESSION['outdoor'] = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (validIndoor($_POST['indoor'])) {
            $_SESSION['indoor'] = $_POST['indoor'];
        } else {
            $errors[] = 'indoor';
        }

        if (validOutdoor($_POST['outdoor'])) {
            $_SESSION['outdoor'] = $_POST['outdoor'];
        } else {
            $errors[] = 'outdoor';
        }

        if (empty($errors))
        {
            $f3->reroute('summary');
        }
    }

    $f3->set('errors', $errors);
    $f3->set('indoor', indoor);
    $f3->set('outdoor', outdoor);
    $view = new Template();
    echo $view->render('views/form3.html');
});

// final page, combines interests and concatenates elements to a string
$f3->route('GET /summary', function() {

    $indoor = isset($_SESSION['indoor']) ? $_SESSION['indoor'] : [];
    $outdoor = isset($_SESSION['outdoor']) ? $_SESSION['outdoor'] : [];

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