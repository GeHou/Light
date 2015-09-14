<?php

require '/Light/Loader.php';
require '/Light/Helpers.php';

$loader = new \Light\Loader;
$loader->register();
$loader->addNamespace('Light', '../Light');

$aliases = array(
    'Config' => 'Light\\Config',
    'Route' => 'Light\\Router',
    'Controller' => 'Light\\Controller',
    'View' => 'Light\\View',
    'DB' => 'Light\\Database',
    'Event' => 'Light\\Event',
);

Light\Loader::$aliases = $aliases;
Light\Event::listen(Light\Config::loader, function($file)
{
    return Light\Config::file($file);
});

require '/app/route.php';
