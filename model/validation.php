<?php

function validName($name)
{
    return ctype_alpha($name);
}

function validAge($age)
{
    if (is_numeric($age)) {
        $numAge = intval($age);
        return $numAge >= 18 && $numAge <= 118;
    }

    return false;
}

function validPhone($phone)
{
    return is_numeric($phone) && strlen($phone) == 10;
}

function validEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

define('indoor', [
    'tv', 'movies', 'cooking', 'board-games',
    'puzzles', 'reading', 'playing-cards', 'video-games'
]);

function validIndoor($indoor)
{
    if (empty($indoor)) {
        return false;
    }

    foreach ($indoor as $interest) {
        if (!in_array($interest, indoor)) {
            return false;
        }
    }

    return true;
}

define('outdoor', [
    'hiking', 'biking', 'swimming',
    'collecting', 'walking', 'climbing'
]);

function validOutdoor($outdoor)
{
    if (empty($outdoor)) {
        return false;
    }

    foreach ($outdoor as $interest) {
        if (!in_array($interest, outdoor)) {
            return false;
        }
    }

    return true;
}
