<?php

namespace Lite\Mvc;

class Application extends \Lite\Application
{
    /**
     * @return View|\Lite\View
     */
    public function getView()
    {
        if (!$this->_view) {
            $this->_view = new View($this);
        }

        return $this->_view;
    }
}