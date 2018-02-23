<?php

namespace Core;

class Route
{
    public $name;
    public $pattern;
    public $class;
    public $method;
    public $params;
}

class Router
{
    public static $routes;

    private static function buildRoute($pattern){
        $PATH = Config::getConfiguration()['meta']['APP_PATH'];
        $route = new Route;
        $route->name    = $pattern;
        $route->pattern = $PATH . $pattern;

        $elems = explode('/', trim($pattern, '/'));

        $route->class   = $elems[0];
        $route->method  = $elems[1];
        return $route;
    }

    public static function get($pattern){
        self::$routes["get"][] = self::buildRoute($pattern);
    }

    public static function post($pattern){
        self::$routes["post"][] = self::buildRoute($pattern);
    }

    public static function any($pattern){
        self::$routes["any"][] = self::buildRoute($pattern);
    }

    public function resolve($app_path)
    {
        $matched = false;
        foreach(self::$routes as $method) {
            foreach($method as $route){
                print_r($route->pattern); echo "<br>";
                print_r($app_path);
                if(strpos($app_path, $route->pattern) === 0) {
                    $matched = true;
                    break;
                }
            }
        }

        if(! $matched) throw new \Exception('Could not match route.');

        $param_str = str_replace($route->pattern, '', $app_path);
        $params = explode('/', trim($param_str, '/'));
        $params = array_filter($params);

        $match = clone($route);
        $match->params = $params;

        return $match;
    }
}