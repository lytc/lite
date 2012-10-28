<?php

namespace Lite;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAndRemoveRoute()
    {
        $app = new Application();
        $router = $app->getRouter();

        $this->assertEquals(0, $router->count());

        $route = new Route\Regex('/([\w+])');
        $closure = function() {};
        $router->add($route, $closure);
        $this->assertEquals(1, $router->count());

        $router->remove($route, $closure);
        $this->assertEquals(0, $router->count());
    }

    public function testMatches()
    {
        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI' => '/foo/bar/1'
        ]);
        $router = $app->getRouter();

        $route1 = new \Lite\Route\Regex('/foo/([\w]+)/(\d+)');
        $route2 = new \Lite\Route\Named('/bar/:baz/:id');
        $callback1 = function() {};
        $callback2 = function() {};
        $router->add($route1, $callback1);
        $router->add($route2, $callback2);

        $this->assertEquals([['route' => $route1, 'callback' => $callback1, 'params' => ['bar', 1]]], $router->matches());
    }

    public function testDispatch()
    {
        $this->expectOutputString('bar1');
        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI' => '/foo/bar/1'
        ]);
        $router = $app->getRouter();

        $route = new \Lite\Route\Regex('/foo/([\w]+)/(\d+)');
        $callback = function($bar, $id) {
            echo $bar;
            echo $id;
        };

        $router->add($route, $callback);
        $router->dispatch();
    }

    public function testPassRoute()
    {
        $this->expectOutputString('callback2:bar1');
        $app = new Application();
        $app->setServerVars([
            'REQUEST_URI' => '/foo/bar/1'
        ]);
        $router = $app->getRouter();

        $route1 = new \Lite\Route\Regex('/foo/([\w]+)/(\d+)');
        $route2 = new \Lite\Route\Named('/foo/:bar/:id');
        $callback1 = function($bar, $id) {
            $this->pass();
        };

        $callback2 = function($bar, $id) {
            echo 'callback2:';
            echo $bar;
            echo $id;
        };

        $router->add($route1, $callback1);
        $router->add($route2, $callback2);
        $router->dispatch();
    }
}