<?php 

use Core\Controller;

class Home extends Controller{

    public function index(){
        $this->response->setEncoding("XML");
        $this->response->send(['message'=>'Hello World']);
    }

}