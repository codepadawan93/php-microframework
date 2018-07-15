<?php
    require_once "Core/Loader.class.php";
    use Core\Controller;
    use Core\Config;

    define("UID", Config::getConfiguration()->meta->UID);

    $controller = new Controller();
    $controller->dispatch();

?>
