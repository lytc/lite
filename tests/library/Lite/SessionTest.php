<?php

namespace Lite;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAndSet()
    {
        $session = Session::getInstance();
        $session->set('foo', 'foo');
        $this->assertEquals('foo', $session->get('foo'));
    }
}