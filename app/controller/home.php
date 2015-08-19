<?php

class HomeController extends BaseController {

    public function index()
    {
        $view = View::make('home')->with('title', 'houbin');
        // View::process($view);
    }

}