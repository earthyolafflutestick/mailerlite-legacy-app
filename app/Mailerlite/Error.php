<?php

namespace App\Mailerlite;

class Error
{
    public $message;
    public $details;
    public $code;
    public $count = 0;
    public $records = [];

    public function __construct($message, $details = null, $code = 500)
    {
        $this->message = $message;
        $this->details = $details;
        $this->code = $code;
    }
}
