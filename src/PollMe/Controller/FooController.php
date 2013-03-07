<?php

namespace PollMe\Controller;


class FooController extends BaseController
{
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }
}
