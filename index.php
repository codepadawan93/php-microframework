<?php 
    
    require_once "core/loader.class.php";

    
    define("ABSPATH", Core\Config::getConfiguration()['meta']['APP_PATH']);

    $m = new Core\Model();
   
    $res = $m->DB->query("SELECT * FROM users", true);

    $c = new Core\Controller();

    $c->dispatch();

    $c->response->addHeader("Content-Type: application/json");
    $c->response->send(
        json_encode(
            $res
        )
    );

?>
