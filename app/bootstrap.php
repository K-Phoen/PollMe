<?php

require_once 'autoload.php';

use Rock\Http\Request;
use Rock\Http\Response;


class ApplicationKernel
{
    protected $booted = false;
    protected $services_container = null;


    public function boot()
    {
        if ($this->booted === true) {
            return;
        }

        $this->buildServicesContainer();

        $this->booted = true;
    }

    public function handle(Request $request)
    {
        $this->boot();
        // return $this->getHttpKernel()->handle($request);
        return new Response('response');
    }

    public function getServicesContainer()
    {
        return $this->services_container;
    }

    protected function buildServicesContainer()
    {
        $this->services_container = new Pimple();
    }

    protected function getHttpKernel()
    {
        return $this->services_container['http.kernel'];
    }
}
