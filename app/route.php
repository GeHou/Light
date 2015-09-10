<?php

Route::get('/', function() {
  echo "GET /!";
});

Route::get('/home', 'home@index');
Route::get('/home/db', 'home@db');

Route::get('/foo', function($slug) {
  echo "GET Foo!<br />" . $slug;
});

Route::get('/hou/(:num)', function($num) {
  echo "The number is: {$num}<br />";
});

Route::post('/foo', function() {
  echo "POST Foo!";
});

Route::dispatch();