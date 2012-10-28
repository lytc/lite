<?php

namespace Lite;

class StubSettable
{
    use Settable;

    public $name;

    public function setName($name)
    {
        $this->name = $name;
    }
}

class SettableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Lite\Exception
     * @expectedExceptionMessage Call undefined method foo
     */
    public function testSetterShouldThrowExceptionCallUndefinedMethod()
    {
        $setters = array(
            'foo'   => 'foo',
        );

        $instance = new StubSettable();
        $instance->applySetter($setters);
    }

    public function testSetterShouldNotThrowExceptionWithSlientMode()
    {
        $setters = array(
            'foo'   => 'foo',
        );

        $instance = new StubSettable();
        $instance->applySetter($setters, true);
    }

    public function testSetter()
    {
        $setters = array(
            'name'   => 'foo',
        );

        $instance = new StubSettable();
        $instance->applySetter($setters);

        $this->assertEquals('foo', $instance->name);
    }
}