<?php

namespace Lite\View\Helper;

class Link extends AbstractHelper
{
    /**
     * @var
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
        $view->addMethod('link', function($file = null, array $attributes = []) use($instance) {
            if ($file) {
                $instance->append($file, $attributes);
            }
            return $instance;
        });
    }

    /**
     * @param \Lite\View $view
     * @return Escape
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
     * @param string $file
     * @param array $attributes
     * @return Link
     */
    public function append($file, array $attributes = [])
    {
        $this->_items[] = ['file' => $file, 'attributes' => $attributes];
        return $this;
    }

    /**
     * @param string $file
     * @param array [$attributes]
     * @return Link
     */
    public function appendStyleSheet($file, array $attributes = [])
    {
        $attributes = array_merge(['type' => 'text/css', 'rel' => 'stylesheet'], $attributes);
        return $this->append($file, $attributes);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $result = [];

        foreach ($this->_items as $item) {
            $attributes = [];
            foreach ($item['attributes'] as $name => $value) {
                $attributes[] = sprintf('%s="%s"', $name, $value);
            }
            $result[] = sprintf('<link href="%s"%s>', $item['file'], $attributes? ' ' . implode(' ', $attributes) : '');
        }

        $result = implode(PHP_EOL, $result);

        return $result;
    }
}