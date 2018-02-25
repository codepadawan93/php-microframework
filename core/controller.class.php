<?php

namespace Core;

use Core\Config as Config;
use Core\Request as Request;
use Core\Response as Response;


/*
 *
 *  Class Controller for processing a request and returning a response
 * 
 * 
 */ 
class Controller {
    
    /*
    *
    *  Extension
    *   
    *  @type string
    *
    */ 
    private const EXTENSION = ".class.php";

    private const DIRECTORY = "models/";

    public $request = NULL;

    public $response = NULL;

    public $router = NULL;

    public $url = [];

    /*
    *
    *  Constructor
    *   
    *  @access public
    *  @return void
    *
    */
    public function __construct(){
        $routes = array();
        $this->response = new Response();
        $this->request  = new Request();
        $this->router = new Router();
        $this->url = $this->parse_url();
    }

    /*
    *
    *  Loads a model for use in querying a database
    *   
    *  @access public
    *  @return void
    *
    */ 
    public function load($class){
        
        if(!file_exists( self::DIRECTORY . $class . self::EXTENSION )){
            throw new Exception( sprintf("Exception occurred in %s, line %s: specified file '%s' does not exist.", __FILE__, __LINE__, $class) );
        }else{

            require_once ( self::DIRECTORY . $class . self::EXTENSION);
            $class_name = ucfirst($class);
        
        }

        if(class_exists( $class_name )){
            $this->{$class} = new $class_name;
        }else{
            throw new Exception( sprintf("Exception occurred in %s, line %s: specified model '%s' does not exist.", __FILE__, __LINE__, $class) );
        }

    }

    public function parse_url(){
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return parse_url($url);
    }

    public function action(){
        var_dump(func_get_args());
    }

    public function dispatch(){
        $match  = $this->router->resolve($this->url['path']);
        // Dispatch
        if($match) {
            $match->class = /*"Core\\" .*/ $match->class;
            call_user_func_array(array(new $match->class, $match->method), $match->params);
        }
    }

}