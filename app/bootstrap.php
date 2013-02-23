<?php

require_once 'autoload.php';

use Kunststube\Router\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Rock\Http\Kernel;
use Rock\Http\Request;
use Rock\Http\Controller\ControllerResolver;
use Rock\Routing\Listener\RequestListener;

use Rock\Session\Listener\RequestSessionListener;
use Rock\Session\Session;



use Rock\Http\Response;
class FooController
{
    public function indexAction($name)
    {
        return new Response('Hello ' . $name . '!');
    }
}


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
        $this->registerEvents();

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

        $this->container['session'] = function($c) {
            return new Session();
        };
        $this->container['http.controller.resolver'] = function($c) {
            return new ControllerResolver();
        };
        $this->container['session.request_listener'] = function($c) {
            return new RequestSessionListener($c);
        };
        $this->container['routing.request_listener'] = function($c) {
            return new RequestListener($c['routing.router']);
        };
        $this->container['routing.router'] = function($c) {
            $router = new Router();
            $router->add('/hello', array('controller' => 'FooController::indexAction', 'name' => 'you'));
            $router->add('/hello/:name', array('controller' => 'FooController::indexAction'));
            return $router;
        };

        $this->container['event.dispatcher'] = $this->container->share(function($c) {
            return new EventDispatcher();
        });
        $this->container['http.kernel'] = $this->container->share(function($c) {
            return new Kernel($c['event.dispatcher'], $c['http.controller.resolver']);
        });
    }

    protected function registerEvents()
    {
        $this->container['event.dispatcher']->addSubscriber($this->container['routing.request_listener']);
        $this->container['event.dispatcher']->addSubscriber($this->container['session.request_listener']);
    }

    protected function getHttpKernel()
    {
        return $this->container['http.kernel'];
    }
}
