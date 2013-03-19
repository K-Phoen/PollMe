<?php

namespace Rock\Twig\Extensions;

use Kunststube\Router\Router;

use Rock\Http\Request;


class RoutingTwigExtension extends \Twig_Extension
{
    protected $router;
    protected $request;


    public function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    public function getFunctions()
    {
        return array(
            'route' => new \Twig_SimpleFunction('route', array($this, 'getRoute')),
        );
    }

    public function getRoute($name, array $params = array())
    {
        $baseDir = $this->request->server->get('base_dir');
        return $baseDir . $this->router->reverseRoute($name, $params, array(
            'controller'
        ));
    }

    public function getName()
    {
        return 'routing';
    }
}
