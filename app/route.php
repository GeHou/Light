<?php

use \Light\Router as Route;

Route::get('/', function() {
  echo "GET /!";
}, 'aaa');

Route::get('/(:any)', function($slug) {
  echo "GET Foo!<br />" . $slug;
});

Route::get('/hou', function() {
  echo "GET Foo! Single<br />";
});

Route::post('/foo', function() {
  echo "POST Foo!";
});

Route::dispatch();