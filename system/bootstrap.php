<?php

require 'core/Loader.php';
require 'system/tool/Helper.php';

use system\core\Loader;
use system\core\Event;
use system\core\Config;

Class Light {

    protected static $loader;

    public static function run()
    {
        self::$loader = new Loader;
        self::$loader->register();
        self::$loader->addNamespace('system', './system');
        self::init();
    }

    public static function init()
    {
        Loader::$aliases = array(
            'Loader' => 'system\\core\\Loader',
            'Config' => 'system\\core\\Config',
            'Route' => 'system\\core\\Router',
            'Controller' => 'system\\core\\Controller',
            'View' => 'system\\view\\View',
            'DB' => 'system\\db\\Database',
            'Event' => 'system\\core\\Event',
            'L' => 'system\\tool\\Helper',
        );
        Event::listen(system\core\Config::loader, function($file)
        {
            return system\core\Config::file($file);
        });
        
    }

}

