<?php
// src/Router.php
class Router {
    private $routes = [];
    
    public function add($route, $callback) {
        $this->routes[$route] = $callback;
    }
    
    public function dispatch($route) {
        if (isset($this->routes[$route])) {
            return $this->routes[$route]();
        }
        return $this->routes['404']();
    }
}