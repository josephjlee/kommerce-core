<?php
namespace inklabs\kommerce\Entity\View;

use inklabs\kommerce\Entity as Entity;

class OrderAddress
{
    public $firstName;
    public $lastName;
    public $company;
    public $address1;
    public $address2;
    public $city;
    public $state;
    public $zip5;
    public $zip4;
    public $phone;
    public $email;

    public function __construct(Entity\OrderAddress $orderAddress)
    {
        $this->orderAddress = $orderAddress;

        $this->firstName = $orderAddress->firstName;
        $this->lastName  = $orderAddress->lastName;
        $this->company   = $orderAddress->company;
        $this->address1  = $orderAddress->address1;
        $this->address2  = $orderAddress->address2;
        $this->city      = $orderAddress->city;
        $this->state     = $orderAddress->state;
        $this->zip5      = $orderAddress->zip5;
        $this->zip4      = $orderAddress->zip4;
        $this->phone     = $orderAddress->phone;
        $this->email     = $orderAddress->email;

        return $this;
    }

    public function export()
    {
        unset($this->orderAddress);
        return $this;
    }

    public function withAllData()
    {
        return $this;
    }
}