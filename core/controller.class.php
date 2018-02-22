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

    public $request = NULL;

    public $response = NULL;

    /*
    *
    *  Constructor
    *   
    *  @access public
    *  @return void
    *
    */
    public function __construct(){
        $this->response = new Response();
        $this->request  = new Request();
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
        
        if(!file_exists( $class . self::EXTENSION )){
            throw new Exception( sprintf("Exception occurred in %s, line %s: specified file '%s' does not exist.", __FILE__, __LINE__, $class) );
        }else{

            require_once ($class . self::EXTENSION);
            $class_name = ucfirst($class);
        
        }

        if(class_exists( $class_name )){
            $this->{$class} = new $class_name;
        }else{
            throw new Exception( sprintf("Exception occurred in %s, line %s: specified model '%s' does not exist.", __FILE__, __LINE__, $class) );
        }

    }

}