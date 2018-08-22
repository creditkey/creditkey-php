<?php
    namespace CreditKey\Models;

    class Address
    {
        protected $firstName;
        protected $lastName;
        protected $companyName;
        protected $email;
        protected $address1;
        protected $address2;
        protected $city;
        protected $state;
        protected $zip;
        protected $phoneNumber;

        function __construct($firstName, $lastName, $companyName, $email, $address1, $address2, $city,
            $state, $zip, $phoneNumber)
        {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->companyName = $companyName;
            $this->email = $email;
            $this->address1 = $address1;
            $this->address2 = $address2;
            $this->city = $city;
            $this->state = $state;
            $this->zip = $zip;
            $this->phoneNumber = $phoneNumber;
        }

        public static function fromServiceData($data)
        {
            if (is_null($data))
                return null;
            else
                return new Address($data->first_name, $data->last_name, $data->company_name, $data->email, $data->address1,
                    $data->address2, $data->city, $data->state, $data->zip, $data->phone_number);
        }

        public function getFirstName()
        {
            return $this->firstName;
        }

        public function getLastName()
        {
            return $this->lastName;
        }

        public function getCompanyName()
        {
            return $this->companyName;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getAddress1()
        {
            return $this->address1;
        }

        public function getAddress2()
        {
            return $this->address2;
        }

        public function getCity()
        {
            return $this->city;
        }

        public function getState()
        {
            return $this->state;
        }

        public function getZip()
        {
            return $this->zip;
        }

        public function getPhoneNumber()
        {
            return $this->phoneNumber;
        }

        public function toFormData()
        {
            return array(
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'company_name' => $this->companyName,
                'email' => $this->email,
                'address1' => $this->address1,
                'address2' => $this->address2,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'phone_number' => $this->phoneNumber
            );
        }
    }
?>
