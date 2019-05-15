<?php
/*
 * Author: Evan Wheeler
 * Date: 4/14/19
 * Purpose: To practice using F3 and utilize the MVC design pattern (somewhat)
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

// start up f3
require_once 'vendor/autoload.php';
// load validation functions
require_once 'model/validation.php';

// create an instance and enable debugging
$f3 = Base::instance();
$f3->set('DEBUG', 3);

// start up a session
session_start();

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
            $f3->set('firstName', $_POST['firstName']);
        } else {
            $errors[] = 'firstName';
        }

        if (validName($_POST['lastName'])) {
            $f3->set('lastName', $_POST['lastName']);
        } else {
            $errors[] = 'lastName';
        }

        if (validAge($_POST['age'])) {
            $f3->set('age', $_POST['age']);
        } else {
            $errors[] = 'age';
        }

        if (validPhone($_POST['phone'])) {
            $f3->set('phone', $_POST['phone']);
        } else {
            $errors[] = 'phone';
        }

        if (empty($errors)) {
            if ($_POST['premium']) {
                $_SESSION['member'] = new PremiumMember(
                    $_POST['firstName'], $_POST['lastName'], $_POST['age'], $_POST['gender'], $_POST['phone']
                );
            } else {
                $_SESSION['member'] = new Member(
                    $_POST['firstName'], $_POST['lastName'], $_POST['age'], $_POST['gender'], $_POST['phone']
                );
            }

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
    if (!isset($_SESSION['member'])) {
        // error if the url is typed manually
        $f3->error(403);
    }

    $member = $_SESSION['member'];
    $errors = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (validEmail($_POST['email'])) {
            $member->setEmail($_POST['email']);
        } else {
            $errors[] = 'email';
        }

        if (empty($errors)) {
            $member->setState($_POST['state']);
            $member->setSeeking($_POST['seeking']);
            $member->setBio($_POST['bio']);

            if ($member instanceof PremiumMember) {
                $f3->reroute('interests');
            }

            $f3->reroute('summary');
        }
    }

    $f3->set('errors', $errors);
    $view = new Template();
    echo $view->render('views/form2.html');
});

// 3rd of three forms, collects data from form 2 in session
$f3->route('GET|POST /interests', function($f3)
{
    if (!isset($_SESSION['member']) ||
        !($_SESSION['member'] instanceof PremiumMember)
    ) {
        // error if:
        //   - there is no member object
        //   - the member is not a premium member
        $f3->error(403);
    }

    $errors = array();

    // empty the arrays on this page every time it's loaded
    $f3->set('indoor', array());
    $f3->set('outdoor', array());

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (validIndoor($_POST['indoor'])) {
            $f3->set('indoor', $_POST['indoor']);
        } else {
            $errors[] = 'indoor';
        }

        if (validOutdoor($_POST['outdoor'])) {
            $f3->set('outdoor', $_POST['outdoor']);
        } else {
            $errors[] = 'outdoor';
        }

        if (empty($errors))
        {
            $member = $_SESSION['member'];
            $member->setIndoorInterests($_POST['indoor']);
            $member->setOutdoorInterests($_POST['outdoor']);

            $f3->reroute('summary');
        }
    }

    $f3->set('errors', $errors);
    $f3->set('validIndoor', indoor);
    $f3->set('validOutdoor', outdoor);
    $view = new Template();
    echo $view->render('views/form3.html');
});

// final page, combines interests and concatenates elements to a string
$f3->route('GET /summary', function($f3)
{
    if (!isset($_SESSION['member'])) {
        // error if the url is typed manually
        $f3->error(403);
    }

    $indoor = isset($_SESSION['indoor']) ? $_SESSION['indoor'] : [];
    $outdoor = isset($_SESSION['outdoor']) ? $_SESSION['outdoor'] : [];

    $interests = "";

    foreach (array_merge($indoor, $outdoor) as $interest) {
        $interests .= $interest . " ";
    }

    $_SESSION['interests'] = $interests;

    $view = new Template();
    echo $view->render('views/summary.html');
});

// run f3
$f3 -> run();