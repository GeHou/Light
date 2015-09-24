<?php

require 'core/Loader.php';
require 'system/tool/Helper.php';

$loader = new \system\core\Loader;
$loader->register();
$loader->addNamespace('system', './system');

$aliases = array(
    'Loader' => 'system\\core\\Loader',
    'Config' => 'system\\core\\Config',
    'Route' => 'system\\core\\Router',
    'Controller' => 'system\\core\\Controller',
    'View' => 'system\\view\\View',
    'DB' => 'system\\db\\Database',
    'Event' => 'system\\core\\Event',
);

system\core\Loader::$aliases = $aliases;
system\core\Event::listen(system\core\Config::loader, function($file)
{
    return system\core\Config::file($file);
});

require '/app/route.php';
