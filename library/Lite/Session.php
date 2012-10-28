<?php

namespace Lite;

class Session
{
    /**
     * @var Session
     */
    protected static $_instance;
    /**
     * @var array
     */
    protected $_session = [];

    protected function __construct() {

    }

    /**
     * @return Session
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
            if (!self::$_instance->isStated() & !headers_sent()) {
                session_start();
            }

            if (!isset($_SESSION['__LITE__'])) {
                $_SESSION['__LITE__'] = &self::$_instance->_session;
            } else {
                self::$_instance->_session = &$_SESSION['__LITE__'];
            }
        }

        return self::$_instance;
    }

    /**
     * @return bool
     */
    public function isStated()
    {
        return !!session_id();
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->_session[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function &get($name)
    {
        if (isset($this->_session[$name])) {
            return $this->_session[$name];
        }
    }

    public function &getNamespace($name)
    {
        if (!$this->get($name)) {
             $this->set($name, []);
        }
        return $this->get($name);
    }
}