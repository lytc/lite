<?php

namespace Lite;

class Loader
{
    /**
     * @var array
     */
    protected static $_includePath = [];

    /**
     * @param $paths
     */
    public static function addIncludePath($paths = null)
    {
        if (!is_array($paths)) {
            $paths = [$paths];
        }

        self::$_includePath = array_merge(self::$_includePath, $paths);
    }

    protected static function _autoload()
    {
        $includePaths = self::$_includePath;
        $includePaths[substr(__DIR__, 0, -4)] = 'Lite';

        return function($className) use($includePaths) {
            if (class_exists($className, false) || interface_exists($className, false) || trait_exists($className, false)) {
                return;
            }

            $relativePath = str_replace('\\', '/', $className) . '.php';
            foreach ($includePaths as  $path => $namespace) {
                if (is_numeric($path)) {
                    $path = $namespace;
                    $namespace = 0;
                }
                if (is_string($namespace)) {
                    if (0 !== strpos($className, $namespace . '\\')) {
                        continue;
                    }
                }

                $absolutePath = $path . '/' . $relativePath;
                if (!file_exists($absolutePath)) {
                    continue;
                }

                require_once $absolutePath;
            }
        };
    }

    /**
     *
     */
    public static function registerAutoload()
    {
        $autoload = self::_autoload();
        spl_autoload_unregister($autoload);
        spl_autoload_register($autoload);
    }
}