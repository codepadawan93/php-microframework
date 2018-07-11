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
    *   TODO:: find a way to controller === controller/ === controller/index
    */ 
    private static function buildRoute($pattern){
        $PATH = Config::getConfiguration()['meta']['APP_PATH'];
        $route = new Route;
        $route->name    = $pattern;
        $route->pattern = $PATH . str_replace( 'index', '', $pattern ) ;

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


    /**
    *  Resolves a URL, returning the class and method names for the requested action
    *  so a proper response can be sent. All '/' delimited URI segments after the
    *  class/method combination are regarded as parameters
    *
    *  @access public
    *  @return mixed
    *
    */ 
    public function resolve(Request $request)
    {   
        if($request->internalType === "BAD_REQUEST") return false;

        $uri     = $request->url['path'];
        $uri     = str_replace('index', '', $uri);
        $matched = false;
        $uriSegments =  preg_split('@/@', trim($uri), NULL, PREG_SPLIT_NO_EMPTY);

        $candidate = null;
        foreach(self::$routes[$request->internalType] as $i => $route)
        {
            foreach($uriSegments as $j => $uriSegment)
            {
                if(
                    $uriSegment === $route->class && 
                    $uriSegments[$i+1] === $route->method
                )
                {
                    $matched = true;
                }
                elseif(
                    $uriSegment === $route->class && 
                    $uriSegments[$i+1] !== $route->method && 
                    $route->method === "index"
                )
                {
                    $candidate = $route;
                }
            }
            if($matched) break;
        }

        if( !$matched && $candidate !== null) 
        {
            $route = $candidate;
        } elseif ( !$matched ) return false;

        $paramString = str_replace($route->pattern, '', $uri);
        $params      = explode('/', trim($paramString, '/'));

        $match = clone($route);
        $match->params = $params;
        return $match;
    }
}