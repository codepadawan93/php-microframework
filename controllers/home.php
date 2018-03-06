<?php 

use Core\Controller;

class Home extends Controller
{

    public function index()
    {
        $this->response->setEncoding("JSON");
        $this->response->send( func_get_args() );
    }

    public function getMessage()
    {
        $this->response->setEncoding("XML");
        $this->response->send( ['all'=>'hello worlds'] );
    }

}