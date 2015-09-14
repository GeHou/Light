<?php namespace Light;

class Config {

    public static $items = array();

    public static $elements = array();

    public static $cache = array();

    const loader = 'light.config.loader';

    public static function has($key)
    {
        return ! is_null(static::get($key));
    }

    public static function get($key, $default = null)
    {
        list($file, $item) = static::parse($key);

        if ( ! static::load($file)) return value($default);

        $items = static::$items[$file];

        if (is_null($item)) {
            return $items;
        } else {
            return array_get($items, $item, $default);
        }
    }

    public static function set($key, $value)
    {
        list($file, $item) = static::parse($key);

        static::load($file);

        if (is_null($item)) {
            array_set(static::$items, $file, $value);
        } else {
            array_set(static::$items[$file], $item, $value);
        }
    }

    protected static function parse($key)
    {
        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key];
        }

        $segments = explode('.', static::element($key));

        if (count($segments) >= 2) {
            $parsed = array($segments[0], implode('.', array_slice($segments, 1)));
        } else {
            $parsed = array($segments[0], null);
        }

        return static::$cache[$key] = $parsed;
    }

    public static function load($file)
    {
        if (isset(static::$items[$file])) return true;

        $config = Event::first(static::loader, func_get_args());

        if (count($config) > 0) {
            static::$items[$file] = $config;
        }

        return isset(static::$items[$file]);
    }

    public static function file($file)
    {
        $config = array();

        foreach (static::paths() as $directory) {
            if ($directory !== '' and file_exists($path = $directory.$file.EXT)) {
                $config = array_merge($config, require $path);
            }
        }

        return $config;
    }

    protected static function paths()
    {
        $paths[] = APP_PATH.'/config/';

        //@todo 配置环境切换
        // if ( ! is_null(Request::env()))
        // {
        //     $paths[] = $paths[count($paths) - 1].Request::env().'/';
        // }

        return $paths;
    }

    protected static function element($identifier)
    {
        if (isset(static::$elements[$identifier])) {
            return static::$elements[$identifier];
        }

        if (strpos($identifier, '::') !== false) {
            $element = explode('::', strtolower($identifier));
        } else {
            $element = strtolower($identifier);
        }

        return static::$elements[$identifier] = $element;
    }

}