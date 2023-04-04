<?php

namespace App\Mailerlite;

class Stats
{
    public $total;

    public function __construct($total)
    {
        $this->total = $total;
    }
}
