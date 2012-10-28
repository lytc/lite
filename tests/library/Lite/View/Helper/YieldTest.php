<?php

namespace Lite\View\Helper;

class YieldTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $app = new \Lite\Application();
        $view = $app->getView();
        $view->yield('foo', function() {
            return 'foo';
        });

        $this->assertEquals('foo', $view->yield('foo'));
    }
}