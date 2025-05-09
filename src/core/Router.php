<?php
namespace App\Core;

class Router
{
    protected array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    public function get(string $route, $action): void
    {
        $this->routes['GET'][$route] = $action;
    }

    public function post(string $route, $action): void
    {
        $this->routes['POST'][$route] = $action;
    }

    public function run(): void
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method][$uri])) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        $action = $this->routes[$method][$uri];
        if (is_array($action)) {
            [$controllerClass, $methodName] = $action;
            $controller = new $controllerClass;
            call_user_func([$controller, $methodName]);
        } elseif (is_callable($action)) {
            call_user_func($action);
        }
    }
}
