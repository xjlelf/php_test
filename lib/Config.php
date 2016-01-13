<?php

namespace Lib;


class Config {

    private static $config_namespace = NULL;

    public static function load($name) {
        if (is_null(self::$config_namespace)) {
            throw new \Exception("Config namespace is not set yet.");
        }

        static $configs;

        if (!isset($configs[$name])) {
            $class = self::$config_namespace . "\\{$name}";
            $config = new $class();
            $configs[$name] = $config;
        }

        return $configs[$name];
    }

    public static function setConfigNamespace($namespace) {
        self::$config_namespace = $namespace;
    }
} 