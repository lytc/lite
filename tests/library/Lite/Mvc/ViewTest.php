<?php

namespace Lite\Mvc;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderWithLayout()
    {
        $app = new Application();
        $view = $app->view;

        $view->setLayout(__DIR__ . '/fixtures/views/layouts/application.php');
        $actual = $view->render(__DIR__  . '/fixtures/views/scripts/script.php');
        $expected = 'Layoutscript content';

        $this->assertEquals($expected, $actual);
    }

    public function testViewPath()
    {
        $app = new Application();
        $view = $app->view;

        $view->setPath(__DIR__ . '/fixtures/views');
        $view->setLayout('application');
        $actual = $view->render('script');
        $expected = 'Layoutscript content';

        $this->assertEquals($expected, $actual);
    }
}