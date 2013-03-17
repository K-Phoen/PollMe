<?php

namespace Rock\Tests\Http;

use Rock\Http\Controller\ControllerResolver;
use Rock\Http\Request;


class DummyController
{
    public function dummyAction()
    {
    }

    public function __invoke()
    {
    }
}

class ControllerResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;

    protected function setUp()
    {
        $this->resolver = new ControllerResolver();
    }

    public function testGetControllerFromEmptyRequest()
    {
        $request = new Request();
        $this->assertNull($this->resolver->getController($request));
    }

    public function testGetControllerFromRequestAndArray()
    {
        $controller =  array('\Rock\Tests\Http\DummyController', 'dummyAction');

        $request = new Request();
        $request->attributes->set('_controller', $controller);

        $this->assertSame($controller, $this->resolver->getController($request));
    }

    public function testGetControllerFromRequestAndObject()
    {
        $controller = new DummyController();

        $request = new Request();
        $request->attributes->set('_controller', $controller);

        $this->assertSame($controller, $this->resolver->getController($request));
    }

    public function testGetControllerFromRequestAndClassName()
    {
        $controller = '\Rock\Tests\Http\DummyController';

        $request = new Request();
        $request->attributes->set('_controller', $controller);

        $this->assertInstanceof($controller, $this->resolver->getController($request));
    }

    public function testGetControllerFromRequestAndActionName()
    {
        $controller = '\Rock\Tests\Http\DummyController::dummyAction';

        $request = new Request();
        $request->attributes->set('_controller', $controller);

        $controller_result = $this->resolver->getController($request);
        $this->assertCount(2, $controller_result);
        $this->assertInstanceof('\Rock\Tests\Http\DummyController', $controller_result[0]);
        $this->assertEquals('dummyAction', $controller_result[1]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetControllerFromRequestAndInvalidActionClassName()
    {
        $request = new Request();
        $request->attributes->set('_controller', '\Rock\Tests\Http\Foo::dummyAction');

        $this->resolver->getController($request);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetControllerFromRequestAndInvalidActionName()
    {
        $request = new Request();
        $request->attributes->set('_controller', '\Rock\Tests\Http\DummyController::joe');

        $this->resolver->getController($request);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetControllerFromRequestAndInvalidControllerString()
    {
        $request = new Request();
        $request->attributes->set('_controller', 'joe');

        $this->resolver->getController($request);
    }
}
