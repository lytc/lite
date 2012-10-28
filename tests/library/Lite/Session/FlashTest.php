<?php

namespace Lite\Session;

class FlashTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAndSet()
    {
        $flashSession = Flash::getInstance();
        $this->assertNull($flashSession->get('flash-foo'));

        $flashSession->set('flash-foo', 'flash-foo');

        $actual = $flashSession->get('flash-foo');
        $this->assertEquals('flash-foo', $actual);
        $this->assertNull($flashSession->get('flash-foo'));
    }
}