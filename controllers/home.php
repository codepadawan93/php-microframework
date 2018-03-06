<?php 

use Core\Controller;

class Home extends Controller{

    public function index(){
        $this->response->send("Hello World");
    }

}