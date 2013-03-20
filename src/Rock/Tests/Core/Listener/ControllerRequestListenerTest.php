<?php

namespace Rock\Tests\Core\Listener;

use Rock\Core\Controller\RequestAware;
use Rock\Core\Listener\ControllerRequestListener;

use Rock\Http\Event\FilterControllerEvent;
use Rock\Http\Request;


class ControllerRequestListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider controllerProvider
     */
    public function testOnKernelController($controller, $expect_injection)
    {
        $listener = new ControllerRequestListener();
        $request = new Request();

        $listener->onKernelController($this->getEvent($controller, $request));

        if ($expect_injection) {
            if (is_object($controller)) {
                $this->assertSame($request, $controller->request);
            } else {
                $this->assertSame($request, $controller[0]->request);
            }
        } else {
            if (is_object($controller)) {
                $this->assertNull($controller->request);
            } else {
                $this->assertNull($controller[0]->request);
            }
        }
    }

    public function testWithWeirdController()
    {
        $listener = new ControllerRequestListener();
        $controller = 'fooAction';

        $listener->onKernelController($this->getEvent($controller, new Request()));
    }


    public function controllerProvider()
    {
        return array(
            array(new DummyRequestAwareController(), true),
            array(array(new DummyRequestAwareController(), 'fooAction'), true),

            array(new DummyRequestController(), false),
            array(array(new DummyRequestController(), 'fooAction'), false),
        );
    }

    protected function getEvent($controller, $request)
    {
        $kernel = $this->getMock('\Rock\Http\KernelInterface');
        return new FilterControllerEvent($kernel, $controller, $request);
    }
}



class DummyRequestController
{
    public $request;


    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function fooAction()
    {
    }
}

class DummyRequestAwareController extends DummyRequestController implements RequestAware
{
    public function fooAction()
    {
    }
}
