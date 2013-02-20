<?php

namespace Rock\Http\Controller;

use Rock\Http\Request;


class ControllerResolver implements ControllerResolverInterface
{
    public function getController(Request $request)
    {
        return null;
    }

    public function getArguments(Request $request, $controller)
    {
        return array();
    }
}
