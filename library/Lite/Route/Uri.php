<?php

namespace Lite\Route;

class Uri extends AbstractRoute
{
    /**
     * @param string $uri
     * @return array|bool
     */
    public function match($uri)
    {
        if ($uri == $this->_pattern) {
            return $this->_matchConditions([]);
        }

        return false;
    }
}