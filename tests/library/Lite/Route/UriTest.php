<?php

namespace Lite\Route;

class UriTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route = new Uri('/');

        $this->assertEquals([], $route->match('/'));
        $this->assertFalse($route->match('/foo'));

        $route = new Uri('/foo');
        $this->assertEquals([], $route->match('/foo'));
    }
}