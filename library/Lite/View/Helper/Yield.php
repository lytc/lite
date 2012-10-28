<?php

namespace Lite\View\Helper;

class Yield extends AbstractHelper
{
    /**
     * @var ContentFor
     */
    protected static $_instances = [];

    /**
     * @var array
     */
    protected $_items = [];

    /**
     * @param \Lite\View $view
     */
    public function __construct(\Lite\View $view)
    {
        parent::__construct($view);
        $instance = $this;
        $view->addMethod('yield', function($name, \Closure $callback = null) use($instance) {
            if (null === $callback) {
                return $instance->get($name);
            }
            $instance->set($name, $callback);
        });
    }

    /**
     * @param \Lite\View $view
     * @return mixed
     */
    public static function getInstance(\Lite\View $view)
    {
        $objectHash = spl_object_hash($view);

        if (!isset(self::$_instances[$objectHash])) {
            self::$_instances[$objectHash] = new self($view);
        }

        return self::$_instances[$objectHash];
    }

    /**
     * @param $name
     * @param callable $callback
     * @return ContentFor
     */
    public function set($name, \Closure $callback)
    {
        $this->_items[$name] = $callback;
        return $this;
    }

    /**
     * @param $name
     * @return string
     */
    public function get($name)
    {
        if (!isset($this->_items[$name])) {
            return '';
        }

        $callback = $this->_items[$name]->bindTo($this->_view);
        return $callback();
    }
}