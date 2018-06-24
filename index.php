<?php 
    require_once "core/loader.class.php";
    use Core\Controller;

    
    define("UID", Core\Config::getConfiguration()['meta']['UID']);

    $controller = new Core\Controller();
    $controller->dispatch();

?>
