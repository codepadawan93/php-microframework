<?php 

namespace Core;

/*
*   
*   Class request - stores data about the last HTTP request
*
*/
class Request{

    /*
    *   
    *   type - stores the type of the last request as string 
    *
    */
    public $type;

    /*
    *   
    *   GET - stores GET variables as keys in assoc
    *
    */
    public $get = [];

    /*
    *   
    *   POST - stores POST variables as keys in assoc
    *
    */
    public $post = [];

    /*
    *   
    *   Constructor
    *
    *   @access public
    *   @return void
    *
    */
    public function __construct(){
        
        if( isset( $SERVER['REQUEST_TYPE'] ) ){
            $this->type = $SERVER['REQUEST_TYPE'];
        }else{
            $this->type = "BAD_REQUEST";
        }
    }
    
}