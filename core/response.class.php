<?php 

namespace Core;

class Response {

    private $headers = [];

    private $HTTP_status_code = 200;

    public function setHTTPStatusCode($code){
        $numeric_code = (int)$code;
        if( !($numeric_code >= 100 && $numeric_code <=500) ){
            throw new Exception( sprintf("Exception occurred in %s, line %s: specified HTTP response code '%d' is invalid.", __FILE__, __LINE__, $numeric_code) );
        }
        $this->HTTP_status_code = $code;
    }

    public function addHeader($header){
        $this->headers[] = $header;
    }

    public function send($data){
        foreach($this->headers as $header){
            header($header);
        }
        http_response_code( (int)$this->HTTP_status_code );
        echo json_encode($data);
    }
}