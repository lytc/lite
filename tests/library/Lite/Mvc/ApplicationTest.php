<?php

namespace Lite\Mvc;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetView()
    {
        $app = new Application();
        $this->assertInstanceOf('\\Lite\\Mvc\\View', $app->view);
    }
}