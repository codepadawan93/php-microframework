<?php

namespace Core;
/*
 *
 *  Class Loader for loading configuration data, core classes and user-defined classes automatically
 *
 *
 */
class Loader {
    const MODEL_DIR      = "./Models/";
    const CONTROLLER_DIR = "./Controllers/";
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

        // Load user-defined classes. TODO:: security vulnerability here, anything that is under these folders will be executed (plain text, php, js etc)
        if(file_exists(self::MODEL_DIR))
        {
          $models = scandir(self::MODEL_DIR);
          foreach( $models as $model ){
            $file = self::MODEL_DIR . $model;
            if(is_file($file)){
              require_once $file;
            }
          }
        }

        if(file_exists(self::CONTROLLER_DIR))
        {
          $controllers = scandir(self::CONTROLLER_DIR);
          foreach( $controllers as $controller ){
              $file = self::CONTROLLER_DIR . $controller;
              if(is_file($file)){
                  require_once $file;
              }
          }
        }

    }
}

Loader::init();
