<?php

namespace Rock\Http\Event;

use Rock\Http\KernelInterface;
use Rock\Http\Request;


class FilterControllerEvent extends KernelEvent
{
    protected $controller;


    public function __construct(KernelInterface $kernel, $controller, Request $request)
    {
        parent::__construct($kernel, $request);

        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }
}
