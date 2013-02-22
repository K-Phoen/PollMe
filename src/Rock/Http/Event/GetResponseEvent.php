<?php

namespace Rock\Http\Event;

use Rock\Http\Response;


class GetResponseEvent extends KernelEvent
{
    protected $response;


    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;

        $this->stopPropagation();
    }

    public function hasResponse()
    {
        return $this->response !== null;
    }
}
