<?php

namespace Rock\Core\DependencyInjection;

use Kunststube\Router\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Rock\Http\Controller\ControllerResolver;
use Rock\Http\Kernel;

use Rock\Routing\Listener\RequestListener;
use Rock\Routing\Listener\RoutingBootListener;

use Rock\Session\Listener\RequestSessionListener;
use Rock\Session\Session;


class ApplicationContainer extends \Pimple
{
    public function __construct(array $parameters = array())
    {
        $this->registerParameters($parameters);

        $this->registerServices();
        $this->registerListeners();
    }

    protected function registerParameters(array $parameters = array())
    {
        foreach ($parameters as $key => $value) {
            $this[$key] = $value;
        }
    }

    private function registerServices()
    {
        $this['session'] = function($c) {
            return new Session();
        };
        $this['http.controller.resolver'] = function($c) {
            return new ControllerResolver();
        };
        $this['routing.router'] = $this->share(function($c) {
            return new Router();
        });

        $this['event.dispatcher'] = $this->share(function($c) {
            return new EventDispatcher();
        });
        $this['http.kernel'] = $this->share(function($c) {
            return new Kernel($c['event.dispatcher'], $c['http.controller.resolver']);
        });
    }

    private function registerListeners()
    {
        $this['session.request_listener'] = function($c) {
            return new RequestSessionListener($c);
        };
        $this['routing.boot_listener'] = function($c) {
            return new RoutingBootListener($c['config.directory'], $c['routing.router']);
        };
        $this['routing.request_listener'] = function($c) {
            return new RequestListener($c['routing.router']);
        };
    }
}
