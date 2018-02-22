<?php

namespace Core;

/*
 *
 *  Class Config for returning configuration data
 *
 * 
 */ 
class Config{

    private static $configuration = NULL;

    /*
    *
    *  Get configuration data as an assoc
    *
    *  @access public
    *  @return assoc
    * 
    */ 
    public static function getConfiguration(){
        
        if( !file_exists( "config.json" ) ){
            throw new \Exception( sprintf("Exception occurred in %s, line %s: configuration file does not exist.", __FILE__, __LINE__) );
        }

        $config_string = file_get_contents( "config.json" );

        if(!$config_string){
            throw new \Exception( sprintf("Exception occurred in %s, line %s: invalid configuration file.", __FILE__, __LINE__) );
        }

        try{
            self::$configuration = json_decode($config_string, true);
        }catch(\Exception $e){
            printf( "Exception occurred in %s, line %s: ", __FILE__, __LINE__, $e->getMessage() );
        }

        return self::$configuration;
    }
}

?>