<?php

namespace Lite\Route;

class Regex extends AbstractRoute
{
    public function __construct($pattern)
    {
        $pattern = '#^' . $pattern . '$#';
        parent::__construct($pattern);
    }

    public function match($uri)
    {
        if (!preg_match($this->_pattern, $uri, $matches)) {
            return false;
        }

        array_shift($matches);

        return $this->_matchConditions($matches);
    }
}