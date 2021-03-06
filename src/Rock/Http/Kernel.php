<?php

namespace Rock\Http;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Rock\Http\Response;
use Rock\Http\Controller\ControllerResolverInterface;
use Rock\Http\Event\FilterControllerEvent;
use Rock\Http\Event\GetResponseEvent;
use Rock\Http\Event\GetResponseForExceptionEvent;

use Rock\Http\Exception\HttpExceptionInterface;
use Rock\Http\Exception\NotFoundHttpException;


class Kernel implements KernelInterface
{
    protected $dispatcher;
    protected $resolver;


    public function __construct(EventDispatcherInterface $dispatcher, ControllerResolverInterface $resolver)
    {
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

    public function handle(Request $request)
    {
        try {
            return $this->rawHandle($request);
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    protected function rawHandle(Request $request)
    {
        // at this point, we have a "raw" request. We don't know how to handle
        // it, so we trigger with the hope that someone will help us by
        // indicating a controller.

        $event = new GetResponseEvent($this, $request);
        $this->dispatcher->dispatch(KernelEvents::REQUEST, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        // If a way to find the controller to use is contained in the request,
        // the resolver will find it and return a callable.
        $controller = $this->resolver->getController($request);
        if ($controller === null) {
           throw new NotFoundHttpException(sprintf('Unable to find the controller for request "%s".', $request->getPathInfo()));
        }

        $event = new FilterControllerEvent($this, $controller, $request);
        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);
        $controller = $event->getController();

        // controller arguments
        $arguments = $this->resolver->getArguments($request, $controller);

        // call controller
        $response = call_user_func_array($controller, $arguments);

        if (!$response instanceof Response) {
            throw new \LogicException(sprintf('The controller must return a Response instance (got %s).', is_object($response) ? 'object of type ' . get_class($response) : $response));
        }

        return $response;
    }

    protected function handleException(\Exception $e, $request)
    {
        $event = new GetResponseForExceptionEvent($this, $request, $e);
        $this->dispatcher->dispatch(KernelEvents::EXCEPTION, $event);

        // a listener might have replaced the exception
        $e = $event->getException();

        if (!$event->hasResponse()) {
            throw $e;
        }

        $response = $event->getResponse();

        // the developer asked for a specific status code
        if (!$response->isRedirection()) {
            // ensure that we actually have an error response
            if ($e instanceof HttpExceptionInterface) {
                // keep the HTTP status code and headers
                $response->setStatusCode($e->getStatusCode());
                $response->headers->add($e->getHeaders());
            } else {
                $response->setStatusCode(500);
            }
        }

        return $response;
    }
}
