<?php

namespace Rock\Routing\Listener;

use Kunststube\Router\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

use Rock\Core\ApplicationEvents;


class RoutingBootListener implements EventSubscriberInterface
{
    protected $configDir;
    protected $router;


    public function __construct($configDir, Router $router)
    {
        $this->configDir = $configDir;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ApplicationEvents::BOOT => array(array('onApplicationBoot', 30)),
        );
    }

    public function onApplicationBoot()
    {
        foreach (Yaml::parse($this->configDir . '/routing.yml') as $route_name => $route) {
            $route = array_merge(array('defaults' => array()), $route);
            $this->router->add($route['pattern'], array_merge(array('controller' => $route['controller']), $route['defaults']));
        }
    }
}
