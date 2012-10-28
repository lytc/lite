<?php

namespace Lite\Route;

class Named extends Regex
{
    protected $_regexPattern;

    public function __construct($pattern)
    {
        $pattern = preg_quote($pattern, '#');
        $pattern = preg_replace(['#\\\\\:[\w\-]+#', '#\\\\\*#', '#\\\\\?#'], ['([\w\-]+)', '/?(.+)', '?'], $pattern);

        parent::__construct($pattern);
    }
}