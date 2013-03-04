<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;


class FooController extends Controller
{
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }
}
