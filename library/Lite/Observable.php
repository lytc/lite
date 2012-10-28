<?php

namespace Lite;

trait Observable
{
    /**
     * @var array
     */
    protected $_listeners = [];

    /**
     * @param string $eventName
     * @param Closure [$closure]
     * @return bool
     */
    public function hasEventListener($eventName, \Closure $closure = null)
    {
        if (empty($this->_listeners[$eventName])) {
            return false;
        }

        if ($closure && !in_array($closure, $this->_listeners[$eventName])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $eventName
     * @param Closure $closure
     * @return Observable
     */
    public function addEventListener($eventName, \Closure $closure)
    {
        if (!isset($this->_listeners[$eventName])) {
            $this->_listeners[$eventName] = [];
        }

        $this->_listeners[$eventName][] = $closure;

        return $this;
    }

    /**
     * @param string $eventName
     * @param Closure [$closure]
     * @return Observable
     */
    public function removeEventListener($eventName, \Closure $closure = null)
    {
        if (!$this->hasEventListener($eventName)) {
            return $this;
        }

        if (!$closure) {
            unset($this->_listeners[$eventName]);
        } else {
            foreach ($this->_listeners[$eventName] as $key => $item) {
                if ($item === $closure) {
                    unset($this->_listeners[$eventName][$key]);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $eventName
     * @param mixed [$args*]
     * @return Observable
     */
    public function notifyEvent($eventName/*, $args*/)
    {
        if (!$this->hasEventListener($eventName)) {
            return $this;
        }

        $args = func_get_args();
        array_shift($args);

        foreach ($this->_listeners[$eventName] as $closure) {
            $closure = $closure->bindTo($this);
            call_user_func_array($closure, $args);
        }

        return $this;
    }
}