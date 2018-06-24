<?php

namespace Core;

use Core\Config;
use Core\Request;
use Core\Response;


/*
 *
 *  Class Controller for processing a request and returning a response
 * 
 * 
 */ 
class Controller {
    
    /*
    *  Extension
    *   
    *  @type string
    *
    */ 
    private const EXTENSION = ".class.php";


    /*
    *  Extension
    *   
    *  @type string
    *
    */ 
    private const DIRECTORY = "models/";


    /*
    *  Request instance
    *   
    *  @type Core\Request
    *
    */ 
    public $request = NULL;


    /*
    *  Response instance
    *   
    *  @type Core\Response
    *
    */ 
    public $response = NULL;


    /*
    *  Router instance
    *   
    *  @type Router
    *
    */ 
    public $router = NULL;

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
        $this->router   = new Router();
    }

    /*
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


    /*
    *  Calls the appropriate function on the base of the current URL
    *   
    *  @access public
    *  @return void
    *
    */ 
    public function dispatch(){
        $match  = $this->router->resolve($this->request);

        if($match) {
            call_user_func_array(array(new $match->class, $match->method), $match->params);
        }else{
            $this->response->setHTTPStatusCode(404);
            $this->response->send([
                "message" => "Not Found",
                "success"   => false
            ]);
            exit;
        }
    }

}