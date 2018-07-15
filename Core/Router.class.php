<?php

namespace Core;

use Core\Route;

/*
*  Router that actually does the mapping
*/
class Router
{
    /**
    *  Static array of routes
    *
    *  @access public
    *
    */
    public static $routes;


    /**
    *  Creates a route from a pattern
    *
    *  @access private
    *  @return Route
    */
    private static function buildRoute($pattern){
        $PATH = Config::getConfiguration()->meta->APP_PATH;
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

    /**
    *  Maps a pattern to a route that can be accessed via method GET
    *
    *  @access public
    *  @return void
    *
    */
    public static function get($pattern, $target = ''){
        self::$routes["get"][] = self::buildRoute($pattern);
    }


    /**
    *  Maps a pattern to a route that can be accessed via method POST
    *
    *  @access public
    *  @return void
    *
    */
    public static function post($pattern){
        self::$routes["post"][] = self::buildRoute($pattern);
    }


    /**
    *  Maps a pattern to a route that can be accessed via any method
    *
    *  @access public
    *  @return void
    *
    */
    public static function any($pattern){
        self::$routes["any"][] = self::buildRoute($pattern);
    }

    /**
    * Sets the default constroller class
    *
    *  @access public
    *  @return void
    *
    */
    public static function default($className){
        $route = new Route;
        $route->name    = "default";
        $route->pattern =
          Config::getConfiguration()->meta->APP_PATH;
        $route->class   = ucfirst($className);
        $route->method  = "index";
        $route->params  = null;
        self::$routes["default"] = $route;
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
                    isset($uriSegments[$i+1]) &&
                    $uriSegments[$i+1] === $route->method
                )
                {
                    $matched = true;
                }
                elseif(
                    $uriSegment === $route->class &&
                    isset($uriSegments[$i+1]) &&
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
        }
        elseif ( !$matched && isset(self::$routes["default"])) {
            $route = self::$routes["default"];
        }
        elseif ( !$matched ) return false;

        $paramString = str_replace($route->pattern, '', $uri);
        $params      = explode('/', trim($paramString, '/'));

        $match = clone($route);
        $match->params = $params;
        return $match;
    }
}
