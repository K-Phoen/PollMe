<?php

namespace Rock\Http;

use Rock\Http\Response;
use Rock\Http\Controller\ControllerResolverInterface;
use Rock\Http\Exception\NotFoundHttpException;


class Kernel implements KernelInterface
{
    protected $resolver;


    public function __construct(ControllerResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function handle(Request $request)
    {
        // returns a callable or null
        $controller = $this->resolver->getController($request);
        if ($controller === null) {
           throw new NotFoundHttpException(sprintf('Unable to find the controller for request "%s".', $request->getPathInfo()));
        }

        // controller arguments
        $arguments = $this->resolver->getArguments($request, $controller);

        // call controller
        $response = call_user_func_array($controller, $arguments);

        if (!$response instanceof Response) {
            throw new \LogicException('The controller must return a Response instance (got %s).');
        }

        return $response;
    }
}
