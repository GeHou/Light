<?php

define('DS', DIRECTORY_SEPARATOR);
define('EXT', '.php');
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('SYS_PATH', BASE_PATH . '/system');
define('VIEW_BASE_PATH', APP_PATH . '/view/');

require 'system/bootstrap.php';

Light::run();

require APP_PATH . '/route.php';