<?php

class HomeController extends BaseController {

    public function index()
    {
        $view = View::make('home', array('title' => 'houbin', 'fruit' => 'apple'));
        // View::process($view);
    }

}