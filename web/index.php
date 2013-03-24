<?php

require __DIR__ . '/../app/bootstrap.php';

use Rock\Http\Request;


$kernel = new ApplicationKernel();
$kernel->handle(Request::createFromGlobals())->send();
