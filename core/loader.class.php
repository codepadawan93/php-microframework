<?php

namespace Core;
/*
 *
 *  Class Loader for loading configuration data, core classes and user-defined classes automatically
 *
 * 
 */ 
class Loader{
    /*
    *
    *   Method does the loading
    *   
    *   @access public
    *   @return void
    *
    */
    public static function init(){
        
        // Load Core
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

        // Load user-defined classes. TODO:: catastrophic security vulnerability here, anything that is under these folders will be executed (plain text, php, js etc)
        $models = scandir('models/');
        foreach( $models as $model ){
            $file = 'models/' . $model;
            if(is_file($file)){
                require_once $file;
            }
        }

        $controllers = scandir('controllers/');
        foreach( $controllers as $controller ){
            $file = 'controllers/'.$controller;
            if(is_file($file)){
                require_once $file;
            }
        }

    }
}

Loader::init();