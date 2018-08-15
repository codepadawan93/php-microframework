<?php

namespace Core;

use Core\DB as DB;

/*
 *
 *  Class Model - basis from which all models will inherit
 *
 * 
 */ 
class Model {

    /*
    *
    *  Database object
    *
    *  @type DB Object
    * 
    */ 
    public $DB;

    /*
    *
    *  Constructor
    *
    *  @access public
    *  @return Model Object
    * 
    */ 
    public function __construct(){
    
        $this->DB = DB::getInstance();
    
    }

}





?>