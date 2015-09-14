<?php

use Light\Medoo;

class HomeController extends BaseController {

    public function index()
    {
        $view = View::make('home', array('title' => 'houbin', 'fruit' => 'apple'));
    }

    public function db()
    {
        $r = DB::connection('pm25v2')->select('stations', '*');
        var_dump($r);
    }

}