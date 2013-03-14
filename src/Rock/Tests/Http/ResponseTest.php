<?php

namespace Rock\Tests\Http;


class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidStatusCode()
    {
        $response = new TestableResponse('content', 50);
    }

    public function testSetStatusCodeFromConstructor()
    {
        $response = new TestableResponse('content', 200);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getStatusText());
    }

    public function testSendContent()
    {
        $response = new TestableResponse('content');
        $this->expectOutputString('content');

        $response->send();
    }

    /**
     * @dataProvider statusCodeProvider
     */
    public function testSetStatusCode($statusCode, $statusText, $expectedStatusText)
    {
        $response = new TestableResponse('content');
        $response->setStatusCode($statusCode, $statusText);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($expectedStatusText, $response->getStatusText());
    }

    /**
     * @dataProvider redirectionCodeProvider
     */
    public function testIsRedirection($statusCode, $is_redirect)
    {
        $response = new TestableResponse('content');
        $response->setStatusCode($statusCode);

        if ($is_redirect) {
            $this->assertTrue($response->isRedirection());
        } else {
            $this->assertFalse($response->isRedirection());
        }
    }


    public function redirectionCodeProvider()
    {
        return array(
            array(200, false),
            array(404, false),
            array(300, true),
            array(301, true),
            array(302, true),
            array(399, true),
        );
    }

    public function statusCodeProvider()
    {
        return array(
            array(200, null, 'OK'),
            array(200, 'joe', 'joe'),
            array(200, false, ''),
            array('199', null, ''),
        );
    }
}
