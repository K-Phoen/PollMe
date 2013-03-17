<?php

namespace Rock\Tests\Http;

use Rock\Http\Kernel;
use Rock\Http\Request;
use Rock\Http\Response;


class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleWhenResponseObjectIsGiven()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $dispatcher = $this->getDispatcher();
        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->will($this->returnCallback(function($event_type, $event) use ($response) {
                $event->setResponse($response);
            }));

        $kernel = new Kernel($dispatcher, $this->getControllerResolver());
        $this->assertSame($kernel->handle($request), $response);
    }

    /**
     * @expectedException \Rock\Http\Exception\NotFoundHttpException
     */
    public function testControllerResolverReturnsNothing()
    {
        $request = $this->getRequest();

        $dispatcher = $this->getDispatcher();
        $dispatcher->expects($this->exactly(2))->method('dispatch');

        $controller_resolver = $this->getControllerResolver();
        $controller_resolver->expects($this->once())
            ->method('getController')
            ->with($request)
            ->will($this->returnValue(null));

        $kernel = new Kernel($dispatcher, $controller_resolver);
        $kernel->handle($request);
    }

    public function testControllerResolverReturnsController()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $dispatcher = $this->getDispatcher();
        $dispatcher->expects($this->exactly(2))->method('dispatch');

        $controller_resolver = $this->getControllerResolver();
        $controller_resolver->expects($this->once())
            ->method('getController')
            ->with($request)
            ->will($this->returnValue(function() use ($response) {
                return $response;
            }));
        $controller_resolver->expects($this->once())
            ->method('getArguments')
            ->with($request)
            ->will($this->returnValue(array()));

        $kernel = new Kernel($dispatcher, $controller_resolver);
        $this->assertSame($kernel->handle($request), $response);
    }

    /**
     * @expectedException LogicException
     */
    public function testControllerReturnsNoResponseObject()
    {
        $request = $this->getRequest();

        $dispatcher = $this->getDispatcher();
        $dispatcher->expects($this->exactly(3))->method('dispatch');

        $controller_resolver = $this->getControllerResolver();
        $controller_resolver->expects($this->once())
            ->method('getController')
            ->with($request)
            ->will($this->returnValue(function() {
                return 'joe';
            }));
        $controller_resolver->expects($this->once())
            ->method('getArguments')
            ->with($request)
            ->will($this->returnValue(array()));

        $kernel = new Kernel($dispatcher, $controller_resolver);
        $kernel->handle($request);
    }

    protected function getRequest()
    {
        return new Request();
    }

    protected function getResponse()
    {
        return new Response('joe');
    }

    protected function getDispatcher()
    {
        return $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
    }

    protected function getControllerResolver()
    {
        return $this->getMock('\Rock\Http\Controller\ControllerResolverInterface');
    }
}
