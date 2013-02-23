<?php

namespace Rock\Core\Event;

use Symfony\Component\EventDispatcher\Event;

use Rock\Core\ApplicationKernel;


class ApplicationBootEvent extends Event
{
    protected $application;


    public function __construct(ApplicationKernel $application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
        return $this->application;
    }
}
