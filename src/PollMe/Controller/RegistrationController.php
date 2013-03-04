<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;


class RegistrationController extends Controller
{
    public function registerAction()
    {
        return $this->render('register.html.twig');
    }

    public function submitRegisterAction()
    {
        exit('in');
        return $this->render('register.html.twig');
    }
}
