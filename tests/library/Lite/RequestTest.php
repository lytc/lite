<?php

namespace Lite;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUri()
    {
        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI' => '/foo/bar/baz'
        ]);

        $request = $app->getRequest();

        $this->assertEquals('/foo/bar/baz', $request->getUri());
    }

    public function testGetHeader()
    {
        $app = new Application();
        $app->setServerVars([
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate'
        ]);
        $request = $app->getRequest();
        $this->assertEquals('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', $request->getHeader('accept'));
    }

    public function testGetAndSetParams()
    {
        $_GET = [
            'foo'   => 'foo'
        ];

        $_POST = [
            'foo'   => 'foo',
            'bar'   => 'bar'
        ];

        $app = new Application();
        $request = $app->getRequest();

        $expected = array_merge($_GET, $_POST);
        $this->assertEquals($expected, $request->getParams());
    }

    public function testGetAndSetParam()
    {
        $_GET = [
            'foo'   => 'foo'
        ];

        $_POST = [
            'foo'   => 'foo',
            'bar'   => 'bar'
        ];

        $app = new Application();
        $request = $app->getRequest();

        $this->assertEquals('foo', $request->getParam('foo'));

        $request->setParam('baz', 'baz');
        $this->assertEquals('baz', $request->getParam('baz'));
    }

    public function testGetParamGet()
    {
        $_GET = [
            'foo'   => 'foo'
        ];

        $app = new Application();
        $request = $app->getRequest();

        $this->assertEquals('foo', $request->getParamGet('foo'));
    }

    public function testGetParamPost()
    {
        $_POST = [
            'foo'   => 'foo'
        ];

        $app = new Application();
        $request = $app->getRequest();

        $this->assertEquals('foo', $request->getParamPost('foo'));
    }

    public function testGetAndSetParamViaMagicMethod()
    {
        $_GET = [
            'foo'   => 'foo'
        ];

        $_POST = [
            'foo'   => 'foo',
            'bar'   => 'bar'
        ];

        $app = new Application();
        $request = $app->getRequest();

        $this->assertEquals('foo', $request->foo);

        $request->baz = 'baz';
        $this->assertEquals('baz', $request->baz);
    }

    public function testPickParams()
    {
        $_GET = [
            'foo'   => 'foo',
            'bar'   => 'bar'
        ];

        $_POST = [
            'bar'   => 'bar',
            'baz'   => 'baz'
        ];

        $app = new Application();
        $request = $app->getRequest();

        $this->assertEquals(['foo' => 'foo', 'baz' => 'baz'], $request->pickParams('foo', 'baz'));
        $this->assertEquals(['bar' => 'bar', 'baz' => 'baz'], $request->pickParams(['bar', 'baz']));
    }

    public function testGetMethod()
    {
        $app = new Application();
        $app->setServerVars(array(
            'REQUEST_METHOD'    => 'GET'
        ));
        $request = $app->getRequest();
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testGetMethodRewrite()
    {
        $app = new Application();
        $app->setServerVars(array(
            'REQUEST_METHOD'    => 'POST'
        ));
        $_POST['__METHOD__'] = 'PUT';
        $request = $app->getRequest();
        $this->assertEquals('PUT', $request->getMethod());
    }

    public function testIsXhr()
    {
        $app = new Application();
        $app->setServerVars(array(
            'HTTP_X_REQUESTED_WITH'    => 'XMLHttpRequest'
        ));

        $this->assertTrue($app->getRequest()->isXhr());
    }
}