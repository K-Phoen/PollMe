<?php

require_once 'autoload.php';

use Rock\Core\ApplicationKernel as BaseApplicationKernel;


class ApplicationKernel extends BaseApplicationKernel
{
    public function getConfigDir()
    {
      return __DIR__ . '/config';
    }
}
