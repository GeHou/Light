<?php

Route::get('/', function() {
  echo "GET /!";
});

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