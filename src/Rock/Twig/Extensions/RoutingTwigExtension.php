<?php

namespace Rock\Twig\Extensions;

use Kunststube\Router\Router;


class RoutingTwigExtension extends \Twig_Extension
{
    protected $router;


    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            'route' => new \Twig_SimpleFunction('route', array($this, 'getRoute')),
        );
    }

    public function getRoute($name, array $params = array())
    {
        return $this->router->reverseRoute($name, $params, array(
            'controller'
        ));
    }

    public function getName()
    {
        return 'routing';
    }
}
