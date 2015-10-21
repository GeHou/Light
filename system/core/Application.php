<?php

namespace system\core;

use system\core\Ioc;

class Application
{
    protected $basePath;

    protected $storagePath;

    protected $configPath;

    protected $resourcePath;

    protected $loadedConfigurations = [];

    protected $routes = [];

    public $namedRoutes = [];

    protected $groupAttributes;

    protected $middleware = [];

    protected $routeMiddleware = [];

    protected $currentRoute;

    protected $dispatcher;

    protected $namespace;

    public function __construct($basePath = null)
    {
        // date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

        $this->basePath = $basePath;
        $this->bootstrapContainer();
        // $this->registerErrorHandling();
    }

    protected function bootstrapContainer()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('path', $this->path());

        $this->registerContainerAliases();
    }

    protected function registerConfigBindings()
    {
        $this->singleton('config', function () {
            return new ConfigRepository;
        });
    }

    protected function registerRequestBindings()
    {
        $this->singleton('Illuminate\Http\Request', function () {
            return Request::capture()->setUserResolver(function () {
                return $this->make('auth')->user();
            })->setRouteResolver(function () {
                return $this->currentRoute;
            });
        });
    }

    protected function registerViewBindings()
    {
        $this->singleton('view', function () {
            return $this->loadComponent('view', 'Illuminate\View\ViewServiceProvider');
        });
    }

    protected function loadComponent($config, $providers, $return = null)
    {
        $this->configure($config);

        foreach ((array) $providers as $provider) {
            $this->register($provider);
        }

        return $this->make($return ?: $config);
    }

    public function configure($name)
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;

        $path = $this->getConfigurationPath($name);

        if ($path) {
            $this->make('config')->set($name, require $path);
        }
    }

    protected function getConfigurationPath($name)
    {
        $appConfigPath = ($this->configPath ?: $this->basePath('config')).'/'.$name.'.php';

        if (file_exists($appConfigPath)) {
            return $appConfigPath;
        } elseif (file_exists($path = __DIR__.'/../config/'.$name.'.php')) {
            return $path;
        }
    }

    public function withEloquent()
    {
        $this->make('db');
    }

    public function group(array $attributes, Closure $callback)
    {
        $parentGroupAttributes = $this->groupAttributes;

        $this->groupAttributes = $attributes;

        call_user_func($callback, $this);

        $this->groupAttributes = $parentGroupAttributes;
    }

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);

        return $this;
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);

        return $this;
    }

    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);

        return $this;
    }

    public function patch($uri, $action)
    {
        $this->addRoute('PATCH', $uri, $action);

        return $this;
    }

    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);

        return $this;
    }

    public function options($uri, $action)
    {
        $this->addRoute('OPTIONS', $uri, $action);

        return $this;
    }

    public function addRoute($method, $uri, $action)
    {
        $action = $this->parseAction($action);

        if (isset($this->groupAttributes)) {
            if (isset($this->groupAttributes['prefix'])) {
                $uri = trim($this->groupAttributes['prefix'], '/').'/'.trim($uri, '/');
            }

            $action = $this->mergeGroupAttributes($action);
        }

        $uri = '/'.trim($uri, '/');

        if (isset($action['as'])) {
            $this->namedRoutes[$action['as']] = $uri;
        }

        $this->routes[$method.$uri] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    protected function parseAction($action)
    {
        if (is_string($action)) {
            return ['uses' => $action];
        } elseif (! is_array($action)) {
            return [$action];
        }

        return $action;
    }

    protected function mergeGroupAttributes(array $action)
    {
        return $this->mergeNamespaceGroup(
            $this->mergeMiddlewareGroup($action)
        );
    }

    protected function mergeNamespaceGroup(array $action)
    {
        if (isset($this->groupAttributes['namespace']) && isset($action['uses'])) {
            $action['uses'] = $this->groupAttributes['namespace'].'\\'.$action['uses'];
        }

        return $action;
    }

    protected function mergeMiddlewareGroup($action)
    {
        if (isset($this->groupAttributes['middleware'])) {
            if (isset($action['middleware'])) {
                $action['middleware'] = $this->groupAttributes['middleware'].'|'.$action['middleware'];
            } else {
                $action['middleware'] = $this->groupAttributes['middleware'];
            }
        }

        return $action;
    }

    public function middleware(array $middleware)
    {
        $this->middleware = array_unique(array_merge($this->middleware, $middleware));

        return $this;
    }

    public function routeMiddleware(array $middleware)
    {
        $this->routeMiddleware = array_merge($this->routeMiddleware, $middleware);

        return $this;
    }

    public function run($request = null)
    {
        $response = $this->dispatch($request);

        if ($response instanceof SymfonyResponse) {
            $response->send();
        } else {
            echo (string) $response;
        }

        if (count($this->middleware) > 0) {
            $this->callTerminableMiddleware($response);
        }
    }

    public function dispatch($request = null)
    {
        if ($request) {
            $this->instance('Illuminate\Http\Request', $request);
            $this->ranServiceBinders['registerRequestBindings'] = true;

            $method = $request->getMethod();
            $pathInfo = $request->getPathInfo();
        } else {
            $method = $this->getMethod();
            $pathInfo = $this->getPathInfo();
        }

        try {
            return $this->sendThroughPipeline($this->middleware, function () use ($method, $pathInfo) {
                if (isset($this->routes[$method.$pathInfo])) {
                    return $this->handleFoundRoute([true, $this->routes[$method.$pathInfo]['action'], []]);
                }

                return $this->handleDispatcherResponse(
                    $this->createDispatcher()->dispatch($method, $pathInfo)
                );
            });
        } catch (Exception $e) {
            return $this->sendExceptionToHandler($e);
        } catch (Throwable $e) {
            return $this->sendExceptionToHandler($e);
        }
    }

    protected function createDispatcher()
    {
        return $this->dispatcher ?: \FastRoute\simpleDispatcher(function ($r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }

    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    

}
