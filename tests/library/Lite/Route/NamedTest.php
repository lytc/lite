<?php

namespace Lite\Route;

class NamedTest extends \PHPUnit_Framework_TestCase
{
    public function testMatches()
    {
        $route = new Named('/');
        $this->assertEquals([], $route->match('/'));
        $this->assertFalse($route->match('/foo'));

        $route = new Named('/:foo');
        $this->assertEquals(['bar'], $route->match('/bar'));
        $this->assertEquals(['bar-baz'], $route->match('/bar-baz'));
        $this->assertEquals(['bar_baz'], $route->match('/bar_baz'));
        $this->assertFalse($route->match('/bar$baz'));

        $route = new Named('/:foo/:bar');
        $this->assertEquals([ 'bar', 'baz'], $route->match('/bar/baz'));

        $route = new Named('/:foo/*');
        $this->assertEquals(['bar', 'baz/qux/file.xml'], $route->match('/bar/baz/qux/file.xml'));

        $route = new Named('/download/*.*');
        $this->assertEquals(['path/to/file', 'xml'], $route->match('/download/path/to/file.xml'));

        $route = new Named('/posts.?:format?');
        $this->assertEquals([], $route->match('/posts'));
        $this->assertEquals(['json'], $route->match('/posts.json'));
    }
}