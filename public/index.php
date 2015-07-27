<?php

require '../light/autoload.php';

$loader = new \Light\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('Light', '../light');

new \Light\Core;
use \Light\Router as Route;
use \Light\Config as Config;

Config::load();

Route::get('/', function() {
  echo "GET /!";
}, 'aaa');

Route::get('foo', function() {
  echo "GET Foo!";
});

Route::dispatch();
