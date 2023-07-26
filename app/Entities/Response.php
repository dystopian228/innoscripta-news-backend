<?php

namespace App\Entities;
class Response
{
    public mixed $data;
    public string $message;

    public function __construct($data = null, $message = null)
    {
        $this->data = $data;
        $this->message = $message;
    }
}
