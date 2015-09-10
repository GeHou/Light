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
        $database = new Medoo([
            // required
            'database_type' => 'mysql',
            'database_name' => 'pm25v2',
            'server' => 'localhost',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
         
            // optional
            'port' => 3306,
            // driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ]
        ]);
        
        $r = $database->select('stations', '*');
        // var_dump($r);
    }

}