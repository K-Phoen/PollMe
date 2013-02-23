<?php

require_once 'autoload.php';

use Rock\Core\ApplicationKernel as BaseApplicationKernel;


use Rock\Http\Response;
class FooController
{
    public function indexAction($name)
    {
        return new Response('Hello ' . $name . '!');
    }
}


class ApplicationKernel extends BaseApplicationKernel
{
    public function getConfigDir()
    {
      return __DIR__ . '/config';
    }
}
