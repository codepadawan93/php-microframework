<?php
    require_once "Core/Loader.php";
    use Core\Controller;
    use Core\Config;

    define("UID", Config::getConfiguration()->meta->UID);

    $controller = new Controller();
    $controller->dispatch();

?>
