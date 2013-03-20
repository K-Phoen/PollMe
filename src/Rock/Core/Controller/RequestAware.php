<?php

namespace Rock\Core\Controller;

use Rock\Http\Request;


interface RequestAware
{
    public function setRequest(Request $request);
}
