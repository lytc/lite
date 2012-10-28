<?php

namespace Lite;

class FlashMessenger
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
        if (!$session->get('__FLASH_MESSENGER__')) {
            $session->set('__FLASH_MESSENGER__', []);
        }
        $this->_items = &$session->get('__FLASH_MESSENGER__');
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

        if (!is_array($value)) {
            $value = [$value];
        }

        if (!isset($this->_items[$name])) {
            $this->_items[$name] = [];
        }

        $this->_items[$name] = array_merge($this->_items[$name], $value);
    }

    /**
     * @param string [$name]
     * @return mixed
     */
    public function &get($name = null)
    {
        if (!$name) {
            $value = $this->_items;
            $this->_items = [];
            return $value;
        }

        if (isset($this->_items[$name])) {
            $value = $this->_items[$name];
            unset($this->_items[$name]);
            return $value;
        }
    }
}