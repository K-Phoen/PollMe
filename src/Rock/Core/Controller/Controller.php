<?php

namespace Rock\Core\Controller;


abstract class Controller implements ContainerAware
{
    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }
}
