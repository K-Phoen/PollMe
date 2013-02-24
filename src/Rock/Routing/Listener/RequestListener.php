<?php

namespace Rock\Routing\Listener;

use Kunststube\Router\NotFoundException;
use Kunststube\Router\Route;
use Kunststube\Router\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Http\Event\GetResponseEvent;
use Rock\Http\KernelEvents;


class RequestListener implements EventSubscriberInterface
{
    protected $router;


    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 30)),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $this->router->defaultCallback(function(Route $route) use ($request) {
            $request->attributes->set('_controller', $route->dispatchValue('controller'));

            foreach ($route->dispatchValues() as $key => $value) {
                $request->attributes->set($key, $value);
            }
        });

        if ($request->attributes->has('_controller')) {
            // routing is already done
            return;
        }

        try {
            $this->router->routeMethodFromString($request->getMethod(), $request->getRequestUri());
        } catch (NotFoundException $e) {
            // no matching route found
        }
    }
}
