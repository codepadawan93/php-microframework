<?php 

/**
 * Pretty-prints a variable and stops the script
 * 
 * @param mixed
 * @return void
 */
function d($var)
{
    header("Content-Type: text/html; charset=utf-8");
    echo "<pre style=\"position:absolute; background-color: #fff; color:#333;\">";
    if($var === null)
    {
        echo "NULL";
    }else{
        print_r($var);
    }
    echo "</pre>";
    die;
}