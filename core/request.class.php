<?php 

namespace Core;

class Request{

    public $type;

    public $get = [];

    public $post = [];

    public function __construct(){
        
        if( isset( $SERVER['REQUEST_TYPE'] ) ){
            $this->type = $SERVER['REQUEST_TYPE'];
        }else{
            $this->type = "BAD_REQUEST";
        }
    }
}