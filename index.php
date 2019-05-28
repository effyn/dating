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

// create an instance and enable debugging
$f3 = Base::instance();
$f3->set('DEBUG', 3);

// start up a session
session_start();

// define the database connection
$db = new Database();

// root route, shows the landing page
$f3->route('GET /', function()
{
    $f3->
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

    // easy reference to the session member
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

            // if user is a premium member, take them to the interests page
            if ($member instanceof PremiumMember) {
                $f3->reroute('interests');
            }

            // otherwise, insert member into the database and display the summary
            global $db;
            $db->insertMember($member);
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

        // indoor and outdoor are set in the hive for stickiness
        if (isset($_POST['indoor']) && validIndoor($_POST['indoor'])) {
            $f3->set('indoor', $_POST['indoor']);
        } else {
            $errors[] = 'indoor';
        }

        if (isset($_POST['outdoor']) && validOutdoor($_POST['outdoor'])) {
            $f3->set('outdoor', $_POST['outdoor']);
        } else {
            $errors[] = 'outdoor';
        }

        if (empty($errors))
        {
            $member = $_SESSION['member'];
            $member->setIndoorInterests($_POST['indoor']);
            $member->setOutdoorInterests($_POST['outdoor']);

            // insert the PremiumMember into the database, then display summary
            global $db;
            $db->insertMember($member);
            $f3->reroute('summary');
        }
    }

    $f3->set('errors', $errors);

    // load the valid interests into the hive for easy generation
    // these constants were defined in model/validation.php
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

    $view = new Template();
    echo $view->render('views/summary.html');
});

$f3->route('GET /admin', function($f3)
{
    // put the db and members into the hive
    global $db;
    $f3->set('db', $db);
    $f3->set('members', $db->getMembers());

    $view = new Template();
    echo $view->render('views/admin.html');
});

// run f3
$f3 -> run();