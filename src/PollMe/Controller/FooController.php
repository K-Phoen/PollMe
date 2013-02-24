<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;


class FooController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('hello.html.twig', array(
            'name' => $name,
        ));
    }
}
