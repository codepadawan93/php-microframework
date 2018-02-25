<?php 

class Home extends Core\Controller{

    public function index(){
        $this->response->send("Hello World");
    }

}