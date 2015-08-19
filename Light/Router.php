<?php namespace Light;

/**
 * @method static Router get(string $route, Callable $callback)
 * @method static Router post(string $route, Callable $callback)
 * @method static Router put(string $route, Callable $callback)
 * @method static Router delete(string $route, Callable $callback)
 * @method static Router options(string $route, Callable $callback)
 * @method static Router head(string $route, Callable $callback)
 */

class Router {
    public static $routes = array();
    public static $methods = array();
    public static $callbacks = array();
    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );
    public static $errorCallback;
    /**
     * Defines a route w/ callback and method
     */
    public static function __callstatic($method, $params)
    {
        $uri = $params[0];
        // $uri = dirname($_SERVER['PHP_SELF']).$params[0];
        $callback = $params[1];
        if ( $method == 'any' ) {
            self::pushToArray($uri, 'get', $callback);
            self::pushToArray($uri, 'post', $callback);
        } else {
            self::pushToArray($uri, $method, $callback);
        }
    }
    /**
     * Push route items to class arrays
     *
     */
    public static function pushToArray($uri, $method, $callback)
    {
        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }
    /**
     * Defines callback if route is not found
    */
    public static function error($callback)
    {
        self::$errorCallback = $callback;
    }
    /**
     * Runs the callback for the given request
     *
     * $after: Processor After. It will process the value returned by Controller.
     * Example: View@process
     *
     */
    public static function dispatch($after=null)
    {
        $uri = self::detectUri();
        $method = $_SERVER['REQUEST_METHOD'];
        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);
        $foundRoute = false;
        // check if route is defined without regex
        if (in_array($uri, self::$routes)) {
            $routePos = array_keys(self::$routes, $uri);
            foreach ($routePos as $route) {
                if (self::$methods[$route] == $method) {
                    $foundRoute = true;
                    //if route is not an object
                    if(!is_object(self::$callbacks[$route])){
                        //grab all parts based on a / separator
                        $parts = explode('/',self::$callbacks[$route]);
                        //collect the last index of the array
                        $last = end($parts);
                        //grab the controller name and method call
                        $segments = explode('@',$last);
                        //instanitate controller
                        // $controller = new $segments[0]();
                        $controller = Controller::execute($segments[0]);
                        //call method
                        $return = $controller->$segments[1]();
                        if ($after) {
                            $afterSegments = explode('@', $after);
                            $afterSegments[0]::$afterSegments[1]($return);
                        }
                    } else {
                        //call closure
                        call_user_func(self::$callbacks[$route]);
                    }
                }
            }
        } else {
            // check if defined with regex
            $pos = 0;
            foreach (self::$routes as $route) {
                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }
                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method) {
                        $foundRoute = true;
                        array_shift($matched); //remove $matched[0] as [1] is the first parameter.
                        if(!is_object(self::$callbacks[$pos])){
                            //grab all parts based on a / separator
                            $parts = explode('/',self::$callbacks[$pos]);
                            //collect the last index of the array
                            $last = end($parts);
                            //grab the controller name and method call
                            $segments = explode('@',$last);
                            //instanitate controller
                            $controller = new $segments[0]();
                            //call method and pass any extra parameters to the method
                            $return = $controller->$segments[1](implode(",", $matched));
                            if ($after) {
                                $afterSegments = explode('@', $after);
                                $afterSegments[0]::$afterSegments[1]($return);
                            }
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);
                        }
                    }
                }
            $pos++;
            }
        }
        // run the error callback if the route was not found
        if ($foundRoute == false) {
            if (!self::$errorCallback) {
                self::$errorCallback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo '404';
                };
            }
            call_user_func(self::$errorCallback);
        }
    }
    // detect true URI, inspired by CodeIgniter 2
    private static function detectUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }
        if ($uri == '/' || empty($uri)) {
            return '/';
        }
        $uri = parse_url($uri, PHP_URL_PATH);
        // return str_replace(array('//', '../'), '/', trim($uri, '/'));
        return str_replace(array('//', '../'), '/', $uri);
    }
}