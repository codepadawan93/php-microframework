<?php

namespace Core;

use Core\Config as Config;
use PDO;

/*
 *
 *  Class DB for querying the database
 *
 * 
 */ 
class DB {
    /*
    *
    *  PDO DSN string
    *
    *  @type string
    * 
    */ 
    private $DB_DSN;

    /*
    *
    *  Database user
    *
    *  @type string
    * 
    */ 
    private $DB_USER; 
    
    /*
    *
    *  Database password
    *
    *  @type string
    * 
    */ 
    private $DB_PASS;
    
    /*
    *
    *  Internal PDO instance
    *
    *  @type PDO Object
    * 
    */ 
    private $PDO;
    
    /*
    *
    *  Database prefix
    *
    *  @type string
    * 
    */ 
    public $prefix;

    /*
    *
    *  Singleton instance
    *
    *  @type DB Object
    */ 
    private static $instance = NULL;

    /*
    *
    *  Constructor
    *  
    *  @access private
    *  @returns DB Object
    *
    */ 
    private function __construct(){
        try{
            $config = Config::getConfiguration();
        }catch(Exception $e){
            printf( "Exception occurred in %s/%s, line %s: %s", __DIR__, __FILE__, __LINE__, $e->getMessage() );
        }

        $this->DB_DSN  = sprintf(
            "%s:dbname=%s;host=%s",
            $config['database']['driver'],
            $config['database']['name'],
            $config['database']['host']
        );

        $this->DB_USER = $config['database']['user'];
        $this->DB_PASS = $config['database']['password'];
        $this->prefix  = $config['database']['prefix'];
        
        try{

            $this->PDO = new PDO($this->DB_DSN, $this->DB_USER, $this->DB_PASS);

        } catch(Exception $e){

            throw new Exception($e->getMessage());

        }
    }

    /*
    *
    *  Get singleton instance
    * 
    *  @access public
    *  @returns DB Object
    * 
    */ 
    public function getInstance(){
        if(self::$instance === NULL){
            self::$instance = new DB();
            return self::$instance;
        }else{
            return self::$instance;
        }
    }

    /*
    *
    *  Escape dangerous characters in SQL queries
    * 
    *  @access public
    *  @returns string
    * 
    */ 
    public function escape($string){
        return str_replace( ["\\", "\0", "\n", "\r", "\x1a", "'", '"'], ["\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'], $string);
    }

    /*
    *
    *  Get results as associative array
    *  
    *  Second parameter escapes user input
    *
    *  @access public
    *  @params string, (optional)bool
    *  @returns assoc
    * 
    */ 
    public function query($SQL_string, $escape = false){
        if($escape){
            $SQL_string = $this->escape( $SQL_string );
        }

        return $this->PDO->query($SQL_string)->fetchAll(PDO::FETCH_ASSOC);
    }

};

?>