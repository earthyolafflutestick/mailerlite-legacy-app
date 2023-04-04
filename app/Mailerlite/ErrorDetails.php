<?php

namespace App\Mailerlite;

class ErrorDetails
{
    public $message;
    public $errors;

    public function __construct($message = '', $errors = [])
    {
        $this->message = $message;
        $this->errors = $errors;
    }
}
