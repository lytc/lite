<?php

namespace Lite\Mvc;

class View extends \Lite\View
{
    /**
     * @var string
     */
    protected $_path = '';

    /**
     * @var bool
     */
    protected $_enableLayout = true;

    /**
     * @var string
     */
    protected $_layout = 'application';

    /**
     * @var string
     */
    protected $_scriptContent = '';

    /**
     * @var
     */
    protected $_scriptFile;

    /**
     * @param $path
     * @return View
     */
    public function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }

    /**
     * @param $flag
     * @return View
     */
    public function setEnableLayout($flag)
    {
        $this->_enableLayout = (bool) $flag;
        return $this;
    }

    /**
     * @param $layout
     * @return View
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->_scriptContent;
    }

    /**
     * @param string $file
     * @return string
     */
    public function render($file = null)
    {
        if (null === $file) {
            return $this->_contentRendered;
        }

        if (!pathinfo($file, PATHINFO_EXTENSION)) {
            $file .= '.php';
        }

        if (!file_exists($file)) {
            if ($file[0] !== '/') {
                $file = '/' . $file;
            }
            $file = $this->_path . '/scripts' . $file;
        }

        if ($this->_enableLayout) {
            $layoutFile = $this->_layout;
            $this->_scriptContent = parent::render($file);
        } else {
            $layoutFile = $file;
        }

        if (!pathinfo($layoutFile, PATHINFO_EXTENSION)) {
            $layoutFile .= '.php';
        }

        if (!file_exists($layoutFile)) {

            if ($layoutFile[0] !== '/') {
                $layoutFile = '/' . $layoutFile;
            }
            $layoutFile = $this->_path . '/layouts' . $layoutFile;
        }

        return parent::render($layoutFile);
    }
}