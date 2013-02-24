<?php

namespace Rock\Core\Controller;

use Rock\Http\Request;
use Rock\Http\Response;


class ErrorController
{
    public function handleAction(Request $request, \Exception $exception)
    {
        return new Response('Une erreur est survenue : ' . $exception->getMessage());
    }
}
