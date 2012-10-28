<?php

namespace Lite;

trait DynamicMethod
{
    protected $_dynamicMethods = [];

    public function addMethod($methodName, \Closure $callback)
    {
        if (isset($this->_dynamicMethods[$methodName])) {
            throw new Exception("Method $methodName already exists");
        }

        $this->_dynamicMethods[$methodName] = $callback->bindTo($this);
        return $this;
    }

    public function __call($methodName, $args)
    {
        if (!isset($this->_dynamicMethods[$methodName])) {
            throw new Exception("Call undefined method $methodName");
        }

        return call_user_func_array($this->_dynamicMethods[$methodName], $args);
    }
}