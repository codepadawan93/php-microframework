<?php

namespace Core;

use Library\XmlEncoder;

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
    * HTTP status code
    *
    * @access private
    * @type int
    *
    */
    private $HTTP_status_code = 200;

    /**
     * Content type of response  - JSON, XML or plaintext
     *
     * TODO::implement through enum and get rid of the repetition in setEncoding() and send()
     *
     * @access private
     * @type string
     *
     */
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
    *   TODO:: refactor, it's pretty bad
    */
    public function send($data){

        $output = null;
        if($this->encoding === "JSON"){

            $this->addHeader("Content-Type: text/json; charset=utf-8");

            try{
                $output  = json_encode($data);
            }catch(\Exception $e)
            {
                $this->setHTTPStatusCode(500);
                $output = json_encode([
                    "message" => $e->getTraceAsString(),
                    "success" => false
                ]);
            }

        }elseif($this->encoding === "XML"){

            $this->addHeader("Content-Type: text/xml; charset=utf-8");

            try{
                $output  =  XmlEncoder::xml_encode($data);
            }catch(\Exception $e)
            {
                $this->setHTTPStatusCode(500);
                $output = XmlEncoder::xml_encode([
                    "message" => $e->getTraceAsString(),
                    "success" => false
                ]);
            }

        }else{
            $this->addHeader("Content-Type: text/plain; charset=utf-8");
            try{
                $output  = $data;
            }catch(\Exception $e)
            {
                $this->setHTTPStatusCode(500);
                $output = "Internal application error encountered : " . $e->getTraceAsString();
            }

        }

        http_response_code( (int)$this->HTTP_status_code );

        foreach($this->headers as $header){
            header($header);
        }

        echo $output;
    }
}
