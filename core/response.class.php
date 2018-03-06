<?php 

namespace Core;

/*
 *
 *  Class Response - class to manipulate what server will respond to a request
 * 
 */ 
class Response {

    /*
    *   HTTP headers as array
    *   
    *   @access private
    *   @type array
    *
    */ 
    private $headers = [];

    /*
    *   HTTP status code
    *   
    *   @access private
    *   @type int
    *
    */ 
    private $HTTP_status_code = 200;

    private $encoding = "JSON";

    /*
    *   Setter for HTTP status code
    *   
    *   @access public
    *   @return void
    *
    */ 
    public function setHTTPStatusCode($code){
        $numeric_code = (int)$code;
        if( !($numeric_code >= 100 && $numeric_code <=500) ){
            throw new Exception( sprintf("Exception occurred in %s, line %s: specified HTTP response code '%d' is invalid.", __FILE__, __LINE__, $numeric_code) );
        }
        $this->HTTP_status_code = $code;
    }

    /*
    *   Adds a HTTP header to response
    *   
    *   @access public
    *   @return void
    *
    */ 
    public function addHeader($header){
        $this->headers[] = $header;
    }


    /*
    *   Specifies an encoding: json, xml, txt
    *   
    *   @access public
    *   @return void
    *
    */ 
    public function setEncoding($encoding){
        
        /* This is terrible, should refactor */
        if($encoding === "JSON" && !in_array( "Content-Type: text/json; charset=utf-8", $this->headers ) ){

            $this->addHeader("Content-Type: text/json; charset=utf-8");

        }elseif($encoding === "XML" && !in_array( "Content-Type: text/xml; charset=utf-8", $this->headers ) ){

            $this->addHeader("Content-Type: text/xml; charset=utf-8");

        }else{

            $this->addHeader("Content-Type: text/plain; charset=utf-8");
            
        }

        $this->encoding = $encoding;
    }

    /*
    *   Sends the response
    *   
    *   @access public
    *   @return void
    *   TODO:: implement error handling
    */ 
    public function send($data){

        foreach($this->headers as $header){
            header($header);
        }

        http_response_code( (int)$this->HTTP_status_code );

        if($this->encoding === "JSON"){

            echo json_encode($data);

        }elseif($this->encoding === "XML"){

            echo \__XML::xml_encode($data);

        }else{

            echo $data;
            
        }
    }
}