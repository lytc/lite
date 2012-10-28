<?php

namespace Lite;

class View
{
    use DynamicMethod {
        DynamicMethod::__call as protected _callDynamicMethod;
    }

    /**
     * @var Application
     */
    protected $_application;

    /**
     * @var int
     */
    protected $_errorReporting = E_ALL;

    /**
     * @var array
     */
    protected $_vars = array();

    /**
     * @var string
     */
    protected $_file;

    /**
     * @var
     */
    protected $_contentRendered;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->_application = $application;
    }

    /**
     * @param $errorReporting
     * @return View
     */
    public function setErrorReporting($errorReporting)
    {
        $this->_errorReporting = (int) $errorReporting;
        return $this;
    }

    /**
     * @param array $vars
     * @return View
     */
    public function setVars(array $vars)
    {
        $this->_vars = $vars;
        return $this;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return View
     */
    public function assign($name, $value)
    {
        $this->_vars[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getVar($name)
    {
        if (isset($this->_vars[$name])) {
            return $this->_vars[$name];
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return View
     */
    public function __set($name, $value)
    {
        return $this->assign($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getVar($name);
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $args)
    {
        if (!isset($this->_methods[$method])) {
            $helperClass = '\\Lite\\View\\Helper\\' . ucfirst($method);
            if (class_exists($helperClass)) {
                $callable = array($helperClass, 'getInstance');
                if (is_callable($callable)) {
                    call_user_func($callable, $this);
                }
            }
        }

        return $this->_callDynamicMethod($method, $args);
    }

    /**
     * @param string $file
     * @return string
     * @throws Exception
     */
    public function render($file = null)
    {
        if (null === $file) {
            return $this->_contentRendered;
        }

        if (!file_exists($file)) {
            throw new Exception("View file $file does not exists");
        }


        $callback = function() use($file) {
            $this->_file = $file;
            unset($file);

            extract($this->_vars);
            ob_start();
            include $this->_file;
            return ob_get_clean();
        };

        $callback = $callback->bindTo($this);

        $currentErrorReporting = error_reporting();
        error_reporting($this->_errorReporting);
        $result = $callback();
        error_reporting($currentErrorReporting);
        $this->_contentRendered = $result;
        return $result;
    }

    /**
     * @param string $file
     * @return View
     */
    public function display($file)
    {
        echo $this->render($file);
        return $this;
    }
}