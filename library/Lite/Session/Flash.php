<?php

namespace Lite\Session;

class Flash
{
    /**
     * @var
     */
    protected static $_instance;

    /**
     * @var array
     */
    protected $_items = [];

    /**
     *
     */
    protected function __construct()
    {
        $session = \Lite\Session::getInstance();
        if (!$session->get('__FLASH__')) {
            $session->set('__FLASH__', []);
        }
        $this->_items = &$session->get('__FLASH__');
    }

    /**
     * @return Flash
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param string|array $name
     * @param mixed [$value]
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->set($k, $v);
            }
            return;
        }

        $this->_items[$name] = $value;
    }

    /**
     * @param string [$name]
     * @return mixed
     */
    public function &get($name = null)
    {
        if (!$name) {
            return $this->_items;
        }

        if (isset($this->_items[$name])) {
            $value = $this->_items[$name];
            unset($this->_items[$name]);
            return $value;
        }
    }
}