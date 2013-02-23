<?php

namespace PollMe\Controller;

use Rock\Http\Response;


class FooController
{
    public function indexAction($name)
    {
        return new Response('Hello ' . $name . '!');
    }
}
