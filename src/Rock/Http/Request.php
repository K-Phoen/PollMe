<?php

namespace Rock\Http;

class Request
{
    public static function createFromGlobals()
    {
        return new Request();
    }
}
