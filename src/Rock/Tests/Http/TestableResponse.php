<?php

namespace Rock\Tests\Http;

use Rock\Http\Response;

class TestableResponse extends Response
{
    public function getStatusText()
    {
        return $this->statusText;
    }
}
