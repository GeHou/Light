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
        $r = DB::connection()->select('stations', '*');
        var_dump($r);
    }

}