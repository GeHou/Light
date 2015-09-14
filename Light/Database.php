<?php namespace Light;

use Light\Medoo;
use PDO;

class Database {

    public static $connections = array();

    public static function connection($connection = null)
    {
        if (is_null($connection)) $connection = Config::get('db.default');

        if ( ! isset(static::$connections[$connection])) {
            $config = Config::get("db.connections.{$connection}");

            if (is_null($config)) {
                throw new \Exception("Database connection is not defined for [$connection].");
            }

            static::$connections[$connection] = new Medoo([
                // required
                'database_type' => $config['driver'],
                'database_name' => $config['database'],
                'server' => $config['host'],
                'username' => $config['username'],
                'password' => $config['password'],
                'charset' => $config['charset'],
             
                // optional
                'port' => 3306,
                // driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
                'option' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]
            ]);
        }

        return static::$connections[$connection];
    }


}