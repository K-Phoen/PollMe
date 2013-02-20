<?php

namespace Rock\Http\Exception;

interface HttpExceptionInterface
{
    public function getStatusCode();
    public function getHeaders();
}
