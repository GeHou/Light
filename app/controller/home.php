<?php

class HomeController extends BaseController {

    public function index()
    {
        View::make('home', array('title' => 'houbin'));
    }

}