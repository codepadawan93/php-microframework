<?php

use Core\Controller;

class Test extends Controller
{
    public function index()
    {
        $this->response->setEncoding("XML");
        $this->response->send( ['all'=>'hello worlds'] );
    }

}
