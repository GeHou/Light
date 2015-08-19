<?php

class HomeController extends BaseController {

    public function index()
    {
        View::make2('home', array());
    }

}