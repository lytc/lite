<?php

namespace Lite;

class StubObserver
{
    use Observable;
}

class ObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testAddEventListener()
    {
        $instance = new StubObserver();
        $result = $instance->addEventListener('foo', function() {});

        $this->assertSame($instance, $result);
        $this->assertTrue($instance->hasEventListener('foo'));
    }

    public function testHasEventListener()
    {
        $instance = new StubObserver();

        $this->assertFalse($instance->hasEventListener('foo'));

        $closure = function() {};
        $instance->addEventListener('foo', $closure);

        $this->assertTrue($instance->hasEventListener('foo'));
        $this->assertTrue($instance->hasEventListener('foo', $closure));
        $this->assertFalse($instance->hasEventListener('foo', function() {}));
    }

    public function testRemoveEventListener()
    {
        $instance = new StubObserver();

        $fooClosure = function() {};
        $barClosure = function() {};

        $instance->addEventListener('foo', $fooClosure);
        $instance->addEventListener('bar', $barClosure);

        $result = $instance->removeEventListener('foo', $fooClosure);
        $this->assertSame($instance, $result);
        $this->assertFalse($instance->hasEventListener('foo', $fooClosure));

        $instance->removeEventListener('bar', function() {});
        $this->assertTrue($instance->hasEventListener('bar', $barClosure));

        $instance->removeEventListener('bar');
        $this->assertFalse($instance->hasEventListener('bar'));
    }


    public function testNotifyEvent()
    {
        $instance = new StubObserver();

        $result = new \stdClass();
        
        $closure = function($param1, $param2) use(&$result) {
            $result->foo = $param1;
            $result->bar = $param2;
            $result->this = $this;
        };

        $param1 = 'foo';
        $param2 = 'bar';

        $instance->addEventListener('foo', $closure);
        $instance->notifyEvent('foo', $param1, $param2);

        $this->assertEquals($result->foo, 'foo');
        $this->assertEquals($result->bar, 'bar');
        $this->assertSame($result->this, $instance);
    }
}