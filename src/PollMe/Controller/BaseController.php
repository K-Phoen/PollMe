<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;
use Rock\Http\Exception\HttpException;


class BaseController extends Controller
{
    protected function requireUser()
    {
        if ($this->request->attributes->get('_user') === null) {
            throw new HttpException(401, 'Connexion obligatoire');
        }
    }
}
