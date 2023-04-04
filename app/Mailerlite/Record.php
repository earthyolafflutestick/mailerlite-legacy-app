<?php

namespace App\Mailerlite;

class Record
{
    public $id;
    public $email;
    public $name;
    public $country;
    public $subscribeDate;
    public $subscribeTime;

    public function __construct($id, $email, $name, $country, $subscribeDate, $subscribeTime)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->country = $country;
        $this->subscribeDate = $subscribeDate;
        $this->subscribeTime = $subscribeTime;
    }
}
