<?php

require '/Light/Loader.php';

$loader = new \Light\Loader;
$loader->register();
$loader->addNamespace('Light', '../Light');

$aliases = array(
    'Config' => 'Light\\Config',
    'Route' => 'Light\\Router',
    'Controller' => 'Light\\Controller',
    'View' => 'Light\\View',
    'DB' => 'Light\\Database',
);

Light\Loader::$aliases = $aliases;

require '/app/route.php';
