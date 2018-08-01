<?php

namespace CreditKey\Models;

class Address
{
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $address1;
    protected $address2;
    protected $city;
    protected $state;
    protected $zip;
    protected $phoneNumber;

    public function getFirstName()
    {
        return $firstName;
    }

    public function setFirstName($setFirstName)
    {
        $firstName = $setFirstName;
    }

    public function getLastName()
    {
        return $lastName;
    }

    public function setLastName($setLastName)
    {
        $lastName = $setLastName;
    }

    public function getEmail()
    {
        return $email;
    }

    public function setEmail($setEmail)
    {
        $email = $setEmail;
    }

    public function getAddress1()
    {
        return $address1;
    }

    public function setAddress1($setAddress1)
    {
        $address1 = $setAddress1;
    }

    public function getAddress2()
    {
        return $address2;
    }

    public function setAddress2($setAddress2)
    {
        $address2 = $setAddress2;
    }

    public function getCity()
    {
        return $city;
    }

    public function setCity($setCity)
    {
        $city = $setCity;
    }

    public function getState()
    {
        return $state;
    }

    public function setState($setState)
    {
        $state = $setState;
    }

    public function getZip()
    {
        return $zip;
    }

    public function setZip($setZip)
    {
        $zip = $setZip;
    }

    public function getPhoneNumber()
    {
        return $phoneNumber;
    }

    public function setPhoneNumber($setPhoneNumber)
    {
        $phoneNumber = $setPhoneNumber;
    }
}

?>
