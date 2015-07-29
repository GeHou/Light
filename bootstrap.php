<?php

require '/Light/Psr4AutoloaderClass.php';

$loader = new \Light\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('Light', '../Light');

use \Light\Config as Config;

require '/app/route.php';
