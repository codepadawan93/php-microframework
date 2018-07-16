<?php

namespace Core;

/*
 *
 *  Class Config for returning configuration data
 *
 *
 */
class Config {

    private static $configuration = null;

    /** 
    *
    *  Get configuration data as object
    *
    *  @access public
    *  @return stdClass
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
            self::$configuration = json_decode($config_string);
        }catch(\Exception $e){
            printf( "Exception occurred in %s, line %s: ", __FILE__, __LINE__, $e->getMessage() );
        }
        
        return self::$configuration;
    }
}

?>
