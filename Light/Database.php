<?php namespace Light;

use Light\Medoo;
use PDO;

class Database {

    public static function connection()
    {
        return $database = new Medoo([
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
    }


}