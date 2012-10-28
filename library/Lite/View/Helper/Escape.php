<?php

namespace Lite\View\Helper;

class Escape extends AbstractHelper
{
    /**
     * @var
     */
    protected static $_instances = [];

    /**
     * @param \Lite\View $view
     */
    public function __construct(\Lite\View $view)
    {
        parent::__construct($view);
        $view->addMethod('escape', function($str, $flags = 2, $encoding = 'UTF-8', $doubleEncode = true) {
            return htmlentities($str, $flags, $encoding, $doubleEncode);
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
}