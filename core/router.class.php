<?php

namespace Core;

/*
*  A Route entity for storing infoirmation about mapping URL patterns to functions
*/ 
class Route
{
    public $name;
    public $pattern;
    public $class;
    public $method;
    public $params;
}

/*
*  Router that actually does the mapping
*/ 
class Router
{
    /*
    *  Static array of routes
    *
    *  @access public
    *
    */ 
    public static $routes;


    /*
    *  Creates a route from a pattern
    *
    *  @access private
    *  @return Route
    *
    */ 
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
    /*
    *  Maps a pattern to a route that can be accessed via method GET
    *
    *  @access public
    *  @return void
    *
    */ 
    public static function get($pattern, $target = ''){
        self::$routes["get"][] = self::buildRoute($pattern);
    }


    /*
    *  Maps a pattern to a route that can be accessed via method POST
    *
    *  @access public
    *  @return void
    *
    */ 
    public static function post($pattern, $target = ''){
        self::$routes["post"][] = self::buildRoute($pattern);
    }


    /*
    *  Maps a pattern to a route that can be accessed via any method
    *
    *  @access public
    *  @return void
    *
    */ 
    public static function any($pattern, $target = ''){
        self::$routes["any"][] = self::buildRoute($pattern);
    }


    /*
    *  Resolves a URL
    *
    *  @access public
    *  @return bool
    *
    */ 
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

        if(! $matched) return false;

        $param_str = str_replace($route->pattern, '', $app_path);
        $params = explode('/', trim($param_str, '/'));
        $params = array_filter($params);

        $match = clone($route);
        $match->params = $params;

        return $match;
    }
}