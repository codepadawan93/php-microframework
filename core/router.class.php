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
        $route->pattern = $PATH . str_replace( '/index', '', $pattern ) ;

        $elems = explode('/', trim($pattern, '/'));

        $route->class   = $elems[0];
        if( !isset($elems[1]) || $elems[1] === ''){
            $route->method = 'index';
        }else{
            $route->method  = $elems[1];
        }
        return $route;
    }

    /* TODO:: split logic into pattern and callbacks */
    public static function get($pattern, $target = ''){
        self::$routes["get"][] = self::buildRoute($pattern);
    }

    public static function post($pattern, $target = ''){
        self::$routes["post"][] = self::buildRoute($pattern);
    }

    public static function any($pattern, $target = ''){
        self::$routes["any"][] = self::buildRoute($pattern);
    }

    public function resolve($app_path)
    {
        $matched = false;
        foreach(self::$routes as $method) {
            foreach($method as $route){
                if(strpos($app_path, $route->pattern) === 0 || strpos($app_path, $route->pattern . 'index') === 0) {
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