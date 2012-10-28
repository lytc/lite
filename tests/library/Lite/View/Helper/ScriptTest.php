<?php

namespace Lite\View\Helper;

class ScriptTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $app = new \Lite\Application();
        $view = $app->getView();
        $view->script('foo.js');
        $view->script()->append('bar.js');
        $view->script()->append('baz.js', ['type' => 'text/javascript']);

        $expected = [
            '<script src="foo.js"></script>',
            '<script src="bar.js"></script>',
            '<script src="baz.js" type="text/javascript"></script>',
        ];
        $expected = implode(PHP_EOL, $expected);

        $this->assertEquals($expected, '' . $view->script());
    }
}