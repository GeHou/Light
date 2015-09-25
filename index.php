<?php

define('DS', DIRECTORY_SEPARATOR);
define('EXT', '.php');
define('APP_PATH', './app');
define('SYS_PATH', './system');
define('VIEW_BASE_PATH', APP_PATH.'/app/view/');

require 'system/bootstrap.php';

Light::run();

require APP_PATH. '/route.php';