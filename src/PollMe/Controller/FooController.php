<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;
use Rock\Http\Response;


class FooController extends Controller
{
    public function indexAction($name)
    {
        return new Response('Hello ' . $name . '!');
    }
}
