<?php

namespace Lite\Route;

class RegexTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route = new Regex('/');
        $this->assertEquals([], $route->match('/'));
        $this->assertFalse($route->match('/foo'));

        $route = new Regex('/([\w\-]+)');
        $this->assertEquals(['bar'], $route->match('/bar'));
        $this->assertEquals(['bar-baz'], $route->match('/bar-baz'));
        $this->assertFalse($route->match('/bar$baz'));
        $this->assertFalse($route->match('/bar/baz'));

        $route = new Regex('/([\w\-]+)/([\w\-]+)');
        $this->assertEquals(['bar', 'baz'], $result = $route->match('/bar/baz'));

        $route = new Regex('/([\w\-]+)/?(.*)');
        $this->assertEquals(['bar', ''], $route->match('/bar'));
        $this->assertEquals(['bar', 'baz'], $route->match('/bar/baz'));

        $route = new Regex('/([\w\-]+)/(\d+)');
        $this->assertEquals(['bar', 1], $route->match('/bar/1'));
        $this->assertFalse($route->match('/bar/baz'));

        $route = new Regex('/([\w\-]+)/bar/(\d+)');
        $this->assertEquals(['foo', 1], $route->match('/foo/bar/1'));
    }

    public function testMatchWithCondition()
    {
        $route = new Regex('/([\w]+)');

        $route->addCondition(function($foo) {
            return $foo == 'bar';
        });

        $this->assertEquals(['bar'], $route->match('/bar'));
        $this->assertFalse($route->match('/baz'));
    }
}