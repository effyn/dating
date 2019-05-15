<?php

class PremiumMember extends Member
{
    private $_indoorInterests;
    private $_outdoorInterests;

    /**
     * @return array
     */
    function getIndoorInterests()
    {
        return $this->_indoorInterests;
    }

    /**
     * @param array $indoorInterests
     */
    function setIndoorInterests($indoorInterests)
    {
        $this->_indoorInterests = $indoorInterests;
    }

    /**
     * @return array
     */
    function getOutdoorInterests()
    {
        return $this->_outdoorInterests;
    }

    /**
     * @param array $outdoorInterests
     */
    function setOutdoorInterests($outdoorInterests)
    {
        $this->_outdoorInterests = $outdoorInterests;
    }

    /**
     * @return string
     */
    function interestString()
    {
        $allInterests = '';
        foreach (array_merge(
            $this->_indoorInterests, $this->_outdoorInterests) as $interest) {
            $allInterests .= $interest . ' ';
        }
        return $allInterests;
    }
}