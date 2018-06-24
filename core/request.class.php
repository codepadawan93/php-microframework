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
    *   internal type - the framework's internal representation of 
    *   an HTTP verb
    *
    */
    public $internalType;

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
    *  URL array
    *   
    *  @type array
    *
    */ 
    public $url = [];

    /*
    *  Parses current URL
    *   
    *  @access public
    *  @return assoc
    *
    */ 
    public function parse_url(){
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return parse_url($url);
    }

    /*
    *   
    *   Constructor
    *
    *   @access public
    *   @return void
    *
    */
    public function __construct(){
        if( isset( $_SERVER['REQUEST_METHOD'] ) ){
            $this->type = $_SERVER['REQUEST_METHOD'];
            $this->internalType = strtolower($_SERVER['REQUEST_METHOD']);
        }else{
            $this->type = "BAD_REQUEST";
            $this->internalType = "BAD_REQUEST";
        }
        $this->url = $this->parse_url();
    }
    
}