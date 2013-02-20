<?php

namespace Rock\Http;

use Rock\Collections\FrozenMap;


class Request
{
    public $query;
    public $request;
    public $attributes;
    public $cookies;
    public $files;
    public $server;
    protected $content;


    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->request = new FrozenMap($request);
        $this->query = new FrozenMap($query);
        $this->attributes = new FrozenMap($attributes);
        $this->cookies = new FrozenMap($cookies);
        $this->files = new FrozenMap($files);
        $this->server = new FrozenMap($server);

        $this->content = $content;
    }

    public static function createFromGlobals()
    {
        return new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMethod()
    {
        return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }

    public function getRequestUri()
    {
         return $this->server->get('REQUEST_URI');
    }

    public function getPathInfo()
    {
        $requestUri = $this->getRequestUri();

        // Remove the query string from REQUEST_URI
        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        return $requestUri;
    }
}
