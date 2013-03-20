<?php

namespace Rock\Tests\Core\Listener;

use Rock\Core\Controller\ContainerAware;
use Rock\Core\Listener\ControllerContainerListener;

use Rock\Http\Event\FilterControllerEvent;
use Rock\Http\Request;


class ControllerContainerListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider controllerProvider
     */
    public function testOnKernelController($controller, $expect_injection)
    {
        $container = array('42' => 'foo'); // because the container can be anything
        $listener = new ControllerContainerListener($container);

        $listener->onKernelController($this->getEvent($controller));

        if ($expect_injection) {
            if (is_object($controller)) {
                $this->assertSame($container, $controller->container);
            } else {
                $this->assertSame($container, $controller[0]->container);
            }
        } else {
            if (is_object($controller)) {
                $this->assertNull($controller->container);
            } else {
                $this->assertNull($controller[0]->container);
            }
        }
    }

    public function testWithWeirdController()
    {
        $container = array('42' => 'foo'); // because the container can be anything
        $listener = new ControllerContainerListener($container);
        $controller = 'fooAction';

        $listener->onKernelController($this->getEvent($controller));
    }


    public function controllerProvider()
    {
        return array(
            array(new DummyContainerAwareController(), true),
            array(array(new DummyContainerAwareController(), 'fooAction'), true),

            array(new DummyContainerController(), false),
            array(array(new DummyContainerController(), 'fooAction'), false),
        );
    }

    protected function getEvent($controller)
    {
        $kernel = $this->getMock('\Rock\Http\KernelInterface');
        $request = new Request();

        return new FilterControllerEvent($kernel, $controller, $request);
    }
}



class DummyContainerController
{
    public $container;


    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function fooAction()
    {
    }
}

class DummyContainerAwareController extends DummyContainerController implements ContainerAware
{
    public function fooAction()
    {
    }
}
