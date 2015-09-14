<?php

use Light\Medoo;

class HomeController extends BaseController {

    public function index()
    {
        $view = View::make('home', array('title' => 'houbin', 'fruit' => 'apple'));
        // View::process($view);
    }

    public function db()
    {
        var_dump(Config::get('db.connections'));
        exit;
        $r = DB::connection()->select('stations', '*');
        var_dump($r);
    }

}