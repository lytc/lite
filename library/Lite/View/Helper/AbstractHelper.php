<?php

namespace Lite\View\Helper;

abstract class AbstractHelper
{
    /**
     * @var \Lite\View
     */
    protected $_view;

    /**
     * @param \Lite\View $view
     */
    public function __construct(\Lite\View $view)
    {
        $this->_view = $view;
    }
}