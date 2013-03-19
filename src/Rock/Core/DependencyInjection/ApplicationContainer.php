<?php

namespace Rock\Core\DependencyInjection;

use Kunststube\Router\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Rock\Core\Controller\ErrorController;
use Rock\Core\Listener\ControllerContainerListener;
use Rock\Core\Listener\ControllerRequestListener;
use Rock\Core\Listener\ExceptionListener;
use Rock\Core\Listener\RequestContainerListener;

use Rock\Http\Controller\ControllerResolver;
use Rock\Http\Kernel;

use Rock\Twig\Extensions\RoutingTwigExtension;

use Rock\Routing\Listener\RequestBasedirListener;
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

    protected function registerServices()
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
        $this['templating.extension.routing'] = function($c) {
            return new \Twig_Loader_Filesystem($c['templates.directory']);
        };
        $this['templating'] = $this->share(function($c) {
            $twig = new \Twig_Environment($c['templating.loader'], array(
                'cache' => $c['cache.directory'] . '/twig',
                'debug' => $c['debug'],
            ));
            $twig->addExtension(new RoutingTwigExtension($c['routing.router'], $c['request']));

            return $twig;
        });

        // error management
        $this['error_controller'] = function($c) {
            return array(new ErrorController(), 'handleAction');
        };
    }

    protected function registerListeners()
    {
        $this['container.request_container_listener'] = function($c) {
            return new RequestContainerListener($c);
        };
        $this['controller.exception_listener'] = function($c) {
            return new ExceptionListener($c['error_controller']);
        };
        $this['session.request_listener'] = function($c) {
            return new RequestSessionListener($c);
        };
        $this['routing.boot_listener'] = function($c) {
            return new RoutingBootListener($c['config.directory'], $c['routing.router']);
        };
        $this['routing.request_basedir_listener'] = function($c) {
            return new RequestBasedirListener();
        };
        $this['routing.request_listener'] = function($c) {
            return new RequestListener($c['routing.router']);
        };
        $this['controller.controller_container_listener'] = function($c) {
            return new ControllerContainerListener($c);
        };
        $this['controller.controller_request_listener'] = function($c) {
            return new ControllerRequestListener();
        };
    }
}
