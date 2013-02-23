<?php

namespace Rock\Core\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Core\Controller\ContainerAware;
use Rock\Http\Event\FilterControllerEvent;
use Rock\Http\KernelEvents;


class ControllerContainerListener implements EventSubscriberInterface
{
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array(array('onKernelController', 32)),
        );
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_object($controller)) {
            if (!(is_array($controller) && isset($controller[0]) && is_object($controller[0]))) {
                return;
            }

            $controller = $controller[0];
        }

        if ($controller instanceof ContainerAware) {
            $controller->setContainer($this->container);
        }
    }
}
