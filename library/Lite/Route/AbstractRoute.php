<?php

namespace Lite\Route;

abstract class AbstractRoute
{
    /**
     * @var string
     */
    protected $_pattern;

    /**
     * @var Closure
     */
    protected $_conditions = array();

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->_pattern = $pattern;
    }

    /**
     * @param callable $condition
     * @return AbstractRoute
     */
    public function addCondition(\Closure $condition)
    {
        $this->_conditions[] = $condition;
        return $this;
    }

    protected function _matchConditions($matches)
    {
        foreach ($this->_conditions as $condition) {
            if (false === call_user_func_array($condition, $matches)) {
                return false;
            }
        }
        return $matches;
    }

    /**
     * @param string $uri
     * @return bool
     */
    abstract public function match($uri);
}