<?php

class Member
{
    private $_fname;
    private $_lname;
    private $_age;
    private $_gender;
    private $_phone;
    private $_email;
    private $_state;
    private $_seeking;
    private $_bio;

    /**
     * Member constructor.
     * @param string $fname
     * @param string $lname
     * @param int $age
     * @param string $gender
     * @param string $phone
     */
    function __construct($fname, $lname, $age, $gender, $phone)
    {
        $this->_fname = $fname;
        $this->_lname = $lname;
        $this->_age = $age;
        $this->_gender = $gender;
        $this->_phone = $phone;
    }

    /**
     * @return string
     */
    function getFname()
    {
        return $this->_fname;
    }

    /**
     * @param string $fname
     */
    function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * @return string
     */
    function getLname()
    {
        return $this->_lname;
    }

    /**
     * @param string $lname
     */
    function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * @return int
     */
    function getAge()
    {
        return $this->_age;
    }

    /**
     * @param int $age
     */
    function setAge($age)
    {
        $this->_age = $age;
    }

    /**
     * @return string
     */
    function getGender()
    {
        return $this->_gender;
    }

    /**
     * @param string $gender
     */
    function setGender($gender)
    {
        $this->_gender = $gender;
    }

    /**
     * @return string
     */
    function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @param string $phone
     */
    function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * @return string
     */
    function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $email
     */
    function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     */
    function getState()
    {
        return $this->_state;
    }

    /**
     * @param string $state
     */
    function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * @return string
     */
    function getSeeking()
    {
        return $this->_seeking;
    }

    /**
     * @param string $seeking
     */
    function setSeeking($seeking)
    {
        $this->_seeking = $seeking;
    }

    /**
     * @return string
     */
    function getBio()
    {
        return $this->_bio;
    }

    /**
     * @param string $bio
     */
    function setBio($bio)
    {
        $this->_bio = $bio;
    }
}