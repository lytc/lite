<?php

namespace Lite\View\Helper;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $app = new \Lite\Application();
        $view = $app->getView();
        $view->link('favicon.ico', ['rel' => 'shortcut icon']);
        $view->link()->appendStylesheet('foo.css');
        $view->link()->appendStylesheet('bar.css', ['media' => 'screen']);

        $expected = [
            '<link href="favicon.ico" rel="shortcut icon">',
            '<link href="foo.css" type="text/css" rel="stylesheet">',
            '<link href="bar.css" type="text/css" rel="stylesheet" media="screen">',
        ];
        $expected = implode(PHP_EOL, $expected);

        $this->assertEquals($expected, '' . $view->link());
    }
}