<?php

namespace Rock\Core\Controller;

use Rock\Http\Exception\RedirectHttpException;
use Rock\Http\Response;


abstract class Controller implements ContainerAware, RequestAware
{
    protected $container;
    protected $request;


    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function render($template, array $parameters = array())
    {
        $templating = $this->container['templating'];
        $parameters = array_merge(array(
            'request' => $this->request,
        ), $parameters);

        return new Response($templating->render($template, $parameters));
    }

    public function redirect($url)
    {
        throw new RedirectHttpException($url);
    }
}
