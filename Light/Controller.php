<?php namespace Light;

class Controller {

    public static function execute($classname, $parameters = array())
    {
        require APP_PATH . '/controller/base.php';
        require APP_PATH . '/controller/' . $classname . '.php';
        $controller = $classname . 'Controller';
        return new $controller;
    }


}