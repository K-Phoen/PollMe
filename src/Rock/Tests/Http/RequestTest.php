<?php

namespace Rock\Tests\Http;

use Rock\Http\Request;


class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContent()
    {
        $request = new Request();
        $this->assertNull($request->getContent());


        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array(),
            /* $content = */    'foo'
        );
        $this->assertEquals('foo', $request->getContent());
    }

    public function testGetMethod()
    {
        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array(),
            /* $content = */    null
        );
        $this->assertEquals('GET', $request->getMethod());


        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array('REQUEST_METHOD' => 'post'),
            /* $content = */    null
        );
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testGetRequestUri()
    {
        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array(),
            /* $content = */    null
        );
        $this->assertNull($request->getRequestUri());


        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array('REQUEST_URI' => '/joe?joe'),
            /* $content = */    null
        );
        $this->assertEquals('/joe?joe', $request->getRequestUri());
    }

    public function testGetPathInfo()
    {
        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array(),
            /* $content = */    null
        );
        $this->assertNull($request->getPathInfo());


        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array('REQUEST_URI' => '/joe'),
            /* $content = */    null
        );
        $this->assertEquals('/joe', $request->getPathInfo());


        $request = new Request(
            /* $query = */      array(),
            /* $request = */    array(),
            /* $attributes = */ array(),
            /* $cookies = */    array(),
            /* $files = */      array(),
            /* $server = */     array('REQUEST_URI' => '/joe?joe'),
            /* $content = */    null
        );
        $this->assertEquals('/joe', $request->getPathInfo());
    }

    public function testSession()
    {
        $request = new Request();
        $this->assertFalse($request->hasSession());

        $session = $this->getMock('\Rock\Session\SessionInterface');
        $request->setSession($session);
        $this->assertTrue($request->hasSession());
        $this->assertSame($session, $request->getSession());
    }
}
