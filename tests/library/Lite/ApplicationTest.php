<?php
namespace Lite;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testRunShouldNotifyBeforeAndAfterEvent()
    {
        $this->expectOutputString('run.beforerun');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI'   => '/foo'
        ]);

        $app->map('/foo', function() {
        });

        $expectedScopeOfBefore = null;
        $expectedScopeOfAfter = null;

        $app->addEventListener('run.before', function() use(&$expectedScopeOfBefore) {
            $expectedScopeOfBefore = $this;
            echo 'run.before';
        });

        $app->addEventListener('run', function() use(&$expectedScopeOfAfter) {
            $expectedScopeOfAfter = $this;
            echo 'run';
        });

        $app->run();

        $this->assertSame($app, $expectedScopeOfBefore);
        $this->assertSame($app, $expectedScopeOfAfter);
    }

    public function testSetAndGetEnv()
    {
        $app = new Application();

        $_SERVER = array(
            'foo'   => 'foo'
        );

        $this->assertEquals($_SERVER, $app->getServerVars());

        $serverVars = array(
            'foo' => 'foo',
            'bar' => 'bar'
        );
        $app = new Application();
        $app->setServerVars($serverVars);

        $this->assertEquals($serverVars, $app->getServerVars());
    }

    public function testGetRequest()
    {
        $app = new Application();
        $this->assertInstanceOf('\\Lite\Request', $app->getRequest());
    }

    public function testGetResponse()
    {
        $app = new Application();
        $this->assertInstanceOf('\\Lite\\Response', $app->getResponse());
    }

    public function testGetRouter()
    {
        $app = new Application();
        $this->assertInstanceOf('\\Lite\\Router', $app->getRouter());
    }

    public function testGetView()
    {
        $app = new Application();
        $this->assertInstanceOf('\\Lite\\View', $app->getView());
    }

    public function testGetUri()
    {
        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI'   => '/foo/bar/baz'
        ]);
        $this->assertEquals('/foo/bar/baz', $app->getUri());

        $app->setBaseUri('/foo');
        $this->assertEquals('/bar/baz', $app->getUri());
    }

    public function testMap()
    {
        $this->expectOutputString('barbaz');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI'   => '/foo/bar/baz'
        ]);

        $result = $app->map('/foo', function() {});
        $this->assertInstanceOf('\\Lite\\Route\\Uri', $result);

        $result = $app->map('#/foo/(\w+)', function() {});
        $this->assertInstanceOf('\\Lite\\Route\\Regex', $result);

        $result = $app->map('/foo/:bar/:baz', function($bar, $baz) {
            echo $bar.$baz;
        });

        $this->assertInstanceOf('\\Lite\\Route\\Named', $result);


        $app->run();
    }

    public function testGet()
    {
        $this->expectOutputString('expected-output');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_METHOD'    => 'GET',
            'REQUEST_URI'       => '/foo'
        ]);

        $app->get('/foo', function() {
            echo 'expected-output';
        });

        $app->run();
    }

    public function testPost()
    {
        $this->expectOutputString('expected-output');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_METHOD'    => 'POST',
            'REQUEST_URI'       => '/foo'
        ]);

        $app->post('/foo', function() {
            echo 'expected-output';
        });

        $app->run();
    }

    public function testPut()
    {
        $this->expectOutputString('expected-output');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_METHOD'    => 'PUT',
            'REQUEST_URI'       => '/foo'
        ]);

        $app->put('/foo', function() {
            echo 'expected-output';
        });

        $app->run();
    }

    public function testDelete()
    {
        $this->expectOutputString('expected-output');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_METHOD'    => 'DELETE',
            'REQUEST_URI'       => '/foo'
        ]);

        $app->delete('/foo', function() {
            echo 'expected-output';
        });

        $app->run();
    }

    public function testOptions()
    {
        $this->expectOutputString('expected-output');

        $app = new Application();
        $app->setServerVars([
            'REQUEST_METHOD'    => 'OPTIONS',
            'REQUEST_URI'       => '/foo'
        ]);

        $app->options('/foo', function() {
            echo 'expected-output';
        });

        $app->run();
    }

    public function testCleanBuffer()
    {
        $this->expectOutputString('bar');
        echo 'foo';
        $app = new Application();
        $app->cleanBuffer();
        echo 'bar';
    }

    /**
     * @expectedException \Lite\Exception\Halt
     */
    public function testRedirectShowThrowHaltException()
    {
        $app = new Application();
        $app->redirect('/foo');
    }

    public function testRedirectShouldWork()
    {
        try {
            $app = new Application();
            $app->redirect('/foo', 301);
        } catch (\Lite\Exception\Halt $exception) {
            $this->assertEquals('/foo', $app->getResponse()->getHeader('Location'));
            $this->assertEquals(301, $app->response->getStatus());
        }

        try {
            $params = ['foo' => 'foo', 'bar' => 'bar'];
            $app = new Application();
            $app->redirect('/foo', $params);
        } catch (\Lite\Exception\Halt $exception) {
            $this->assertEquals($params, $app->getRequest()->pickParams('foo', 'bar'));
        }
    }

    /**
     * @expectedException \Lite\Exception\Pass
     */
    public function testPassShouldThrowPassException()
    {
        $app = new Application();
        $app->pass();
    }

    /**
     * @expectedException \Lite\Exception\Halt
     */
    public function testHaltShouldThrowHaltException()
    {
        $app = new Application();
        $app->halt();
    }

    public function testHaltShouldWork()
    {
        try {
            $app = new Application();
            $response = $app->getResponse();
            $app->halt();
        } catch (\Lite\Exception\Halt $exception) {
            $this->assertEquals(200, $response->getStatus());
            $this->assertNull($response->getMessage());
        }

        try {
            $app = new Application();
            $response = $app->getResponse();
            $app->halt(410);
        } catch (\Lite\Exception\Halt $exception) {
            $this->assertEquals(410, $response->getStatus());
            $this->assertNull($response->getMessage());
        }

        try {
            $app = new Application();
            $response = $app->getResponse();
            $app->halt('message');
        } catch (\Lite\Exception\Halt $exception) {
            $this->assertEquals(200, $response->getStatus());
            $this->assertEquals('message', $response->getMessage());
        }

        try {
            $app = new Application();
            $response = $app->getResponse();
            $app->halt(401, 'message');
            $this->assertEquals(401, $response->getStatus());
            $this->assertEquals('message', $response->getMessage());
        } catch (\Lite\Exception\Halt $exception) {

        }

        try {
            $app = new Application();
            $response = $app->getResponse();
            $app->halt(402, ['Content-Type' => 'text/plain']);
            $this->assertEquals(402, $response->getStatus());
            $this->assertNull($response->getMessage());
            $this->assertEquals('text/plain', $response->getHeader('Content-Type'));
        } catch (\Lite\Exception\Halt $exception) {

        }

        try {
            $app = new Application();
            $response = $app->getResponse();
            $app->halt(402, 'message', ['Content-Type' => 'text/plain']);
            $this->assertEquals(402, $response->getStatus());
            $this->assertEquals('message', $response->getMessage());
            $this->assertEquals('text/plain', $response->getHeader('Content-Type'));
        } catch (\Lite\Exception\Halt $exception) {

        }
    }

    /**
     * @expectedException \Lite\Exception\Pass
     */
    public function testForwardShouldThrowPassException()
    {
        $app = new Application();
        $app->forward('/foo/bar');
    }

    public function testForwardShouldChangeEnv()
    {
        try {
            $app = new Application();
            $app->forward('/foo/bar');
        } catch (\Lite\Exception\Pass $exception) {
            $this->assertEquals('/foo/bar', $app->getServerVars()['REQUEST_URI']);
        }

        try {
            $app = new Application();
            $app->forward(['REQUEST_URI' => '/foo/bar']);
        } catch (\Lite\Exception\Pass $exception) {
            $this->assertEquals('/foo/bar', $app->getServerVars()['REQUEST_URI']);
        }
    }

    public function testMatch()
    {
        $app = new Application();

        $app->setServerVars([
            'REQUEST_URI'   => '/foo/bar'
        ]);

        $actual = null;
        $scope = null;

        $app->match('/foo', function() use(&$actual, &$scope) {
            $scope = $this;
            $actual = 'foo';
        });

        $this->assertEquals('foo', $actual);
        $this->assertSame($app, $scope);
        $this->assertEquals('/bar', $app->getUri());
    }
}