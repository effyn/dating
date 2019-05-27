<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '/home/ewheeler/config.php';

class Database
{
    /*
    CREATE TABLE member (
        member_id INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(50) NOT NULL,
        lname VARCHAR(50) NOT NULL,
        age INT NOT NULL,
        gender VARCHAR(10) NOT NULL,
        phone VARCHAR(30) NOT NULL,
        email VARCHAR(254) NOT NULL,
        state CHAR(2) NOT NULL,
        seeking VARCHAR(10) NOT NULL,
        bio VARCHAR(300),
        premium TINYINT(1) NOT NULL,
        image VARCHAR(100)
    );

    CREATE TABLE interest (
        interest_id INT AUTO_INCREMENT PRIMARY KEY,
        interest VARCHAR(40) NOT NULL,
        type VARCHAR(10) NOT NULL
    );

    CREATE TABLE member_interest (
        member_id INT NOT NULL,
        FOREIGN KEY (member_id) REFERENCES member(member_id),
        interest_id INT NOT NULL,
        FOREIGN KEY (interest_id) REFERENCES interest(interest_id)
    );
    */

    private $db;

    public function connect()
    {
        $db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWD);
    }

    public function insertMember()
    {

    }

    public function getMembers()
    {

    }

    public function getMember($member_id)
    {

    }

    public function getInterests($member_id)
    {

    }
}
