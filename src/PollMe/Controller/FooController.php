<?php

namespace PollMe\Controller;

use Rock\Core\Controller\ContainerAware;
use Rock\Http\Response;


class FooController implements ContainerAware
{
    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function indexAction($name)
    {
        return new Response('Hello ' . $name . '!');
    }
}
