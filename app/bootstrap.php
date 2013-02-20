<?php

require_once 'autoload.php';

use Rock\Http\Kernel;
use Rock\Http\Request;
use Rock\Http\Controller\ControllerResolver;


class ApplicationKernel
{
    protected $booted = false;
    protected $container = null;


    public function boot()
    {
        if ($this->booted === true) {
            return;
        }

        $this->buildContainer();

        $this->booted = true;
    }

    public function handle(Request $request)
    {
        $this->boot();
        return $this->getHttpKernel()->handle($request);
    }

    public function getContainer()
    {
        return $this->container;
    }

    protected function buildContainer()
    {
        $this->container = new Pimple();

        $this->container['http.controller.resolver'] = $this->container->share(function($c) {
            return new ControllerResolver();
        });
        $this->container['http.kernel'] = $this->container->share(function($c) {
            return new Kernel($c['http.controller.resolver']);
        });
    }

    protected function getHttpKernel()
    {
        return $this->container['http.kernel'];
    }
}
