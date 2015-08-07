<?php

class HomeController extends BaseController {

    public function index()
    {
        View::process('test content <br />');
        echo 'This is HomeController Index method';
    }

}