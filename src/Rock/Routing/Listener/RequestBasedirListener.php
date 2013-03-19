<?php

namespace Rock\Routing\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Http\Event\GetResponseEvent;
use Rock\Http\KernelEvents;


class RequestBasedirListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 80)),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $base_dir = dirname($request->server->get('SCRIPT_NAME'));
        $base_dir = $base_dir === '/' ? '' : $base_dir;

        $requestUri = str_replace($base_dir, '', $request->server->get('REQUEST_URI'));
        $request->server->set('REQUEST_URI', empty($requestUri) ? '/' : $requestUri);
        $request->server->set('base_dir', $base_dir);
    }
}
