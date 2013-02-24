<?php

namespace Rock\Core\DependencyInjection;

use Kunststube\Router\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Rock\Core\Controller\ErrorController;
use Rock\Core\Listener\ControllerContainerListener;
use Rock\Core\Listener\ExceptionListener;

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

        // templating
        $this['templating.loader'] = function($c) {
            return new \Twig_Loader_Filesystem($c['templates.directory']);
        };
        $this['templating'] = $this->share(function($c) {
            return new \Twig_Environment($c['templating.loader'], array(
                'cache' => $c['cache.directory'] . '/twig',
            ));
        });

        // error management
        $this['error_controller'] = function($c) {
            return array(new ErrorController(), 'handleAction');
        };
    }

    private function registerListeners()
    {
        $this['controller.exception_listener'] = function($c) {
            return new ExceptionListener($c['error_controller']);
        };
        $this['session.request_listener'] = function($c) {
            return new RequestSessionListener($c);
        };
        $this['routing.boot_listener'] = function($c) {
            return new RoutingBootListener($c['config.directory'], $c['routing.router']);
        };
        $this['routing.request_listener'] = function($c) {
            return new RequestListener($c['routing.router']);
        };
        $this['controller.controller_container_listener'] = function($c) {
            return new ControllerContainerListener($c);
        };
    }
}
