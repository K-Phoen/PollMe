<?php

namespace Rock\Http;

class Response
{
    protected $body;


    public function __construct($body)
    {
        $this->body = $body;
    }

    public function send()
    {
        echo $this->body;
    }
}
