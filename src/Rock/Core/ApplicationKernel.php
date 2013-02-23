<?php

namespace Rock\Core;

use Rock\Core\Event\ApplicationBootEvent;
use Rock\Core\DependencyInjection\ApplicationContainer;
use Rock\Http\Request;



abstract class ApplicationKernel
{
    protected $booted = false;
    protected $container = null;


    public abstract function getCacheDir();
    public abstract function getConfigDir();


    public function boot()
    {
        if ($this->booted === true) {
            return;
        }

        $this->buildContainer();
        $this->registerEvents();

        $event = new ApplicationBootEvent($this);
        $this->container['event.dispatcher']->dispatch(ApplicationEvents::BOOT, $event);

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
        $this->container = new ApplicationContainer($this->getContainerParameters());
    }

    protected function getContainerParameters()
    {
        return array(
            'cache.directory'   => $this->getCacheDir(),
            'config.directory'  => $this->getConfigDir(),
        );
    }

    protected function registerEvents()
    {
        $this->container['event.dispatcher']->addSubscriber($this->container['routing.request_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['routing.boot_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['session.request_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['controller.controller_container_listener']);
    }

    protected function getHttpKernel()
    {
        return $this->container['http.kernel'];
    }
}
