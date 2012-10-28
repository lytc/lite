<?php

namespace Lite;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    public function testAssign()
    {
        $app = new Application();
        $view = $app->getView();

        $this->assertEquals([], $view->getVars());

        $view->assign('foo', 'foo');
        $this->assertEquals(['foo' => 'foo'], $view->getVars());
        $this->assertEquals('foo', $view->getVar('foo'));

        $view->bar = 'bar';
        $this->assertEquals('bar', $view->getVar('bar'));
        $this->assertEquals('bar', $view->bar);
    }

    /**
     * @expectedException \Lite\Exception
     * @expectedExceptionMessage View file un-existing-file.php does not exists
     */
    public function testRenderShouldThrowExceptionWithNotFoundViewFile()
    {
        $app = new Application();
        $view = $app->getView();

        $view->render('un-existing-file.php');
    }

    public function testRender()
    {
        $app = new Application();
        $view = $app->getView();

        $view->foo = 'foo';
        $view->bar = 'bar';

        $expected = '<div>foo</div><div>bar</div>';
        $actual = $view->render(__DIR__ . '/fixtures/view.php');
        $this->assertEquals($expected, $actual);
    }

    public function testDisplay()
    {
        $this->expectOutputString('<div>foo</div><div>bar</div>');
        $app = new Application();
        $view = $app->getView();

        $view->foo = 'foo';
        $view->bar = 'bar';

        $view->display(__DIR__ . '/fixtures/view.php');
    }

    public function testAddMethod()
    {
        $app = new Application();
        $view = $app->view;

        $view->addMethod('foo', function($str) {
            return $str.'bar';
        });

        $actual = $view->render(__DIR__. '/fixtures/test-add-foo-helper.php');
        $this->assertEquals('foobar', $actual);
    }
}