<?php

class Autoloader {

    private static $loadedNamespaces = array();

    static function loadClass($className)
    {
        $className = str_replace(array('/', '\\'), \DIRECTORY_SEPARATOR, $className);

        $namespaces = explode(\DIRECTORY_SEPARATOR, $className);
        unset($namespaces[sizeof($namespaces)-1]);

        $current=""; foreach($namespaces as $namepart)
        {
            $current.='\\' . $namepart;
            if(in_array($current, self::$loadedNamespaces)) continue;
            self::$loadedNamespaces[] = $current;
        }

        $load = $className . ".php";
        !file_exists($load) ?: require($load);
        return class_exists($className, false);
    }
    static function register()
    {
        spl_autoload_register("Autoloader::loadClass");
    }
    static function unregister()
    {
        spl_autoload_unregister("Autoloader::loadClass");
    }
}

Autoloader::register();
