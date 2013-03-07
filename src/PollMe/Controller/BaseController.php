<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;
use Rock\Http\Exception\HttpException;


class BaseController extends Controller
{
    protected function requireUser()
    {
        if ($this->getUser() === null) {
            throw new HttpException(401, 'Connexion obligatoire');
        }
    }

    protected function getUser()
    {
        return $this->request->attributes->get('_user');
    }
}
