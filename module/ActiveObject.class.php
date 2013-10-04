<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveObject
 *
 * @author jiachi
 */
class ActiveObject {
    
    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    public function __get($name) {
        return $this->{$name};
    }
    
    public static function connect(){
        $mysqli = new mysqli(DB, DB_USER, DB_PASSWORD, DB_NAME);
        if($mysqli->connect_errno){
            throw new Exception("Connect to database fail: ".$mysqli->connect_error);
            exit(1);
        }
        $mysqli->set_charset('utf8');
        return $mysqli;
    }
    
    public static function disconnect($mysqli){
        if($mysqli != null)
            $mysqli->close();
    }
}

?>
