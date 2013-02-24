<?php

namespace Rock\Core\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Http\Event\GetResponseForExceptionEvent;
use Rock\Http\KernelEvents;


class ExceptionListener implements EventSubscriberInterface
{
    protected $controller;


    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array(array('onKernelException', 32)),
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        try {
            $response = call_user_func_array($this->controller, array($request, $exception));
            $event->setResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
