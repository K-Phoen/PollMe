<?php

namespace Rock\Http\Event;

use Rock\Http\KernelInterface;
use Rock\Http\Request;


class GetResponseForExceptionEvent extends GetResponseEvent
{
    protected $exception;


    public function __construct(KernelInterface $kernel, Request $request, \Exception $e)
    {
        parent::__construct($kernel, $request);

        $this->exception = $e;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException(\Exception $e)
    {
        $this->exception = $e;
    }
}
