<?php

namespace Rock\Core\Controller;

use Rock\Http\Exception\NotFoundHttpException;
use Rock\Http\Exception\RedirectHttpException;
use Rock\Http\Request;
use Rock\Http\Response;


abstract class Controller implements ContainerAware, RequestAware
{
    protected $container;
    protected $request;


    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    protected function render($template, array $parameters = array())
    {
        $templating = $this->container['templating'];
        $parameters = array_merge(array(
            'request' => $this->request,
        ), $parameters);

        return new Response($templating->render($template, $parameters));
    }

    protected function redirect($url)
    {
        throw new RedirectHttpException($url);
    }

    protected function createNotFoundException($message = null)
    {
        return new NotFoundHttpException($message);
    }

    protected function buildUrl($name, array $parameters = array())
    {
        $baseDir = $this->request->server->get('base_dir');
        return $baseDir . $this->container['routing.router']->reverseRoute($name, $parameters, array(
            'controller'
        ));
    }
}
