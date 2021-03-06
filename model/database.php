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
        phone CHAR(10) NOT NULL,
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

    static $insertMemberSQL = 'INSERT INTO member VALUES (null, :fname, :lname, :age, :gender, :phone,
:email, :state, :seeking, :bio, :premium, null)';
    static $insertInterestSQL = 'INSERT INTO member_interest VALUES (:id, :interest_id)';

    static $selectInterestIDSQL = 'SELECT interest_id FROM interest WHERE interest=:interest LIMIT 1';

    static $selectMemberInterestsSQL = 'SELECT interest FROM interest, member, member_interest
WHERE member.member_id=:id 
AND member.member_id = member_interest.member_id 
AND interest.interest_id = member_interest.interest_id';
    static $selectMemberIDSQL = 'SELECT * FROM member WHERE member_id=:id LIMIT 1';
    static $selectMembersSQL = 'SELECT * FROM member ORDER BY member_id';

    private $_db;

    /**
     * Database constructor.
     * @return void
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Connects to the database.
     * @return void
     */
    public function connect()
    {
        $this->_db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWD);
    }

    /**
     * Inserts a member into the database. The member should be passed from the session.
     * @param Member $member the member object from the $_SESSION variable
     * @return void
     */
    public function insertMember($member)
    {
        $insert = $this->_db->prepare(self::$insertMemberSQL);

        $fname = $member->getFname();
        $lname = $member->getLname();
        $age = $member->getAge();
        $gender = $member->getGender();
        $phone = $member->getPhone();
        $email = $member->getEmail();
        $state = $member->getState();
        $seeking = $member->getSeeking();
        $bio = $member->getBio();
        $premium = $member instanceof PremiumMember;

        $insert->bindParam(':fname', $fname, PDO::PARAM_STR);
        $insert->bindParam(':lname', $lname, PDO::PARAM_STR);
        $insert->bindParam(':age', $age, PDO::PARAM_INT);
        $insert->bindParam(':gender', $gender, PDO::PARAM_STR);
        $insert->bindParam(':phone', $phone, PDO::PARAM_STR);
        $insert->bindParam(':email', $email, PDO::PARAM_STR);
        $insert->bindParam(':state', $state, PDO::PARAM_STR);
        $insert->bindParam(':seeking', $seeking, PDO::PARAM_STR);
        $insert->bindParam(':bio', $bio, PDO::PARAM_STR);
        $insert->bindParam(':premium', $premium, PDO::PARAM_INT);

        $insert->execute();

        if ($premium == 1) {
            // get the last member_id
            $id = $this->_db->lastInsertId();

            $select = $this->_db->prepare(self::$selectInterestIDSQL);
            $insert = $this->_db->prepare(self::$insertInterestSQL);

            $allInterests = array_merge($member->getIndoorInterests(), $member->getOutdoorInterests());

            foreach ($allInterests as $interest) {
                $select->bindParam(':interest', $interest);

                $select->execute();
                $row = $select->fetch(PDO::FETCH_ASSOC);

                $insert->bindParam(':id', $id, PDO::PARAM_INT);
                $interestID = $row['interest_id'];
                $insert->bindParam(':interest_id', $interestID, PDO::PARAM_INT);

                $insert->execute();
            }
        }
    }

    /**
     * Returns an array of all the members in the database.
     * @return array all members in the database
     */
    public function getMembers()
    {
        $selectMembers = $this->_db->prepare(self::$selectMembersSQL);
        $selectMembers->execute();
        $members = $selectMembers->fetchAll(PDO::FETCH_ASSOC);

        foreach($members as $id=>$member) {
            echo $id;
            // form the full name
            $members[$id]['name'] = "{$member['fname']} {$member['lname']}";

            if ($member['premium'] == 1) {
                // get and set the interests
                $interests = implode(', ', $this->getInterests($id));
                //var_dump($interests);
                $members[$id]['interests'] = $interests;
            }
        }

        return $members;
    }

    /**
     * Returns the fields of a member with the given ID.
     * @param $member_id
     * @return array fields
     */
    public function getMember($member_id)
    {
        $selectMember = $this->_db->prepare(self::$selectMemberIDSQL);
        $selectMember->bindParam(':id', $member_id, PDO::PARAM_INT);
        return $selectMember->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns the interests of a member with the given ID.
     * @param $member_id
     * @return array interests
     */
    public function getInterests($member_id)
    {
        $selectInterests = $this->_db->prepare(self::$selectMemberInterestsSQL);
        $selectInterests->bindParam(':id', $member_id);
        $selectInterests->execute();
        return $selectInterests->fetchAll(PDO::FETCH_NUM);
    }
}
