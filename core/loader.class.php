<?php

namespace Core;

class Loader{
    public static function __init__(){
        
        if(file_exists("classes.json")){
            throw new \Exception( sprintf("Exception occurred in %s, /%s, line %s: There is no classes.json file present.", __DIR__, __FILE__, __LINE__));
        }

        try{
            $classes = json_decode( file_get_contents("core/classes.json"), true);
        } catch (\Exception $e) {
            printf( sprintf("Exception occurred in %s, /%s, line %s: %s", __DIR__, __FILE__, __LINE__, $e->getMessage()) );
        }

        try{
            foreach($classes['files'] as $class){
                require_once $class;
            }    
        } catch (\Exception $e) {
            printf( "Exception occurred in %s, /%s, line %s: %s", __DIR__, __FILE__, __LINE__, $e->getMessage() );
        }

    }
}

Loader::__init__();