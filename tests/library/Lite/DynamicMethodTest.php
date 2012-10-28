<?php

namespace Lite;

class StubDynamicMethod
{
    use DynamicMethod;
}

class DynamicMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testAddMethod()
    {
        $this->expectOutputString('foo');
        $instance = new StubDynamicMethod();
        $actualScope = null;

        $instance->addMethod('foo', function($foo) use(&$actualScope) {
            $actualScope = $this;
            echo $foo;
            return 'bar';
        });
        $result = $instance->foo('foo');

        $this->assertSame($instance, $actualScope);
        $this->assertEquals('bar', $result);
    }

    /**
     * @expectedException \Lite\Exception
     * @expectedExceptionMessage Method foo already exists
     */
    public function testAddMethodShouldThrowExceptionWithExistingMethod()
    {
        $instance = new StubDynamicMethod();
        $instance->addMethod('foo', function() {});
        $instance->addMethod('foo', function() {});
    }

    /**
     * @expectedException \Lite\Exception
     * @expectedExceptionMessage Call undefined method foo
     */
    public function testCallUndefinedMethodShouldThrowException()
    {
        $instance = new StubDynamicMethod();
        $instance->foo();
    }
}