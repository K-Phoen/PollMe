<?php

namespace Rock\Core\Controller;

use Rock\Http\Response;


abstract class Controller implements ContainerAware
{
    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function render($template, array $parameters = array())
    {
        $templating = $this->container['templating'];
        return new Response($templating->render($template, $parameters));
    }
}
