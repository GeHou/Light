<?php

require '/Light/Loader.php';

$loader = new \Light\Loader;
$loader->register();
$loader->addNamespace('Light', '../Light');

$aliases = array(
    'Route' => 'Laravel\\Auth',
    'Auth' => 'Laravel\\Auth',
    );

use \Light\Config as Config;
class_alias('Light\Router', 'Route');
require '/app/route.php';
