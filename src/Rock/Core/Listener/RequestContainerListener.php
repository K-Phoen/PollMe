<?php

namespace Rock\Core\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Http\Event\GetResponseEvent;
use Rock\Http\KernelEvents;


class RequestContainerListener implements EventSubscriberInterface
{
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 99)),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->container['request'] = $event->getRequest();
    }
}
