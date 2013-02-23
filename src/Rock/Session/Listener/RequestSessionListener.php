<?php

namespace Rock\Session\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Http\Event\GetResponseEvent;
use Rock\Http\KernelEvents;


class RequestSessionListener implements EventSubscriberInterface
{
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 32)),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->hasSession()) {
            return;
        }

        $request->setSession($this->container['session']);
    }
}
