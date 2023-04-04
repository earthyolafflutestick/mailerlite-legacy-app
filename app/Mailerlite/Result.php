<?php

namespace App\Mailerlite;

class Result
{
    public $count;
    public $records;

    public function __construct($count = 0, $records = [])
    {
        $this->count = $count;
        $this->records = $records;
    }
}
