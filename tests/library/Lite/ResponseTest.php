<?php

namespace Lite;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAndSetStatus()
    {
        $app = new Application();
        $response = $app->getResponse();

        $this->assertEquals(200, $response->getStatus());

        $response->setStatus(404);
        $this->assertEquals(404, $response->getStatus());
    }

    public function testGetAndSetBody()
    {
        $app = new Application();
        $response = $app->getResponse();

        $this->assertEquals('', $response->getBody());

        $response->setBody('foo');
        $this->assertEquals('foo', $response->getBody());
    }

    public function testGetAndSetHeaders()
    {
        $app = new Application();
        $response = $app->getResponse();

        $expected = ['Content-Type' => 'text/html'];
        $this->assertEquals($expected, $response->getHeaders());

        $headers = ['Content-Type' => 'application/json'];
        $response->setHeaders($headers);
        $this->assertEquals($headers, $response->getHeaders());
    }

    public function testGetAndSetHeader()
    {
        $app = new Application();
        $response = $app->getResponse();

        $this->assertNull($response->getHeader('undefined-header'));
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));

        $response->setHeader('Content-Type', 'application/json');
        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
    }

    public function testSend()
    {
        $this->expectOutputString('foo');

        $app = new Application();
        $response = $app->getResponse();
        $response->setBody('foo');
        $response->send();
    }
}