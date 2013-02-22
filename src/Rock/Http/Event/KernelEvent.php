<?php

namespace Rock\Http\Event;

use Symfony\Component\EventDispatcher\Event;

use Rock\Http\KernelInterface;
use Rock\Http\Request;


class KernelEvent extends Event
{
    protected $kernel;
    protected $request;


    public function __construct(KernelInterface $kernel, Request $request)
    {
        $this->kernel = $kernel;
        $this->request = $request;
    }

    public function getKernel()
    {
        return $this->kernel;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
