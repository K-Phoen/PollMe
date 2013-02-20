<?php

namespace Rock\Http\Controller;

use Rock\Http\Request;


interface ControllerResolverInterface
{
    public function getController(Request $request);
    public function getArguments(Request $request, $controller);
}
