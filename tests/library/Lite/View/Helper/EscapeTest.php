<?php

namespace Lite\View\Helper;

class EscapeTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $app = new \Lite\Application();
        $view = $app->getView();

        $this->assertEquals('&lt;div&gt;', $view->escape('<div>'));
    }
}