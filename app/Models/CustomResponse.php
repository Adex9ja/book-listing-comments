<?php

namespace App\Models;

class CustomResponse
{
    public int $code;
    public string $message;
    public mixed $data;

    public function __construct($message, $data = null, $code = 200)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }
}

