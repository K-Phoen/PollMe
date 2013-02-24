<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;
use Rock\Http\Response;


class FooController extends Controller
{
    public function indexAction($name)
    {
        $templating = $this->container['templating'];
        return new Response($templating->render('hello.html.twig', array(
            'name' => $name,
        )));
    }
}
