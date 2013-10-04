<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Registry serve as a global data storage object,
 * each object that may wants to use global data should
 * composite with this object.
 * 
 * The website should only has one instance of Registry.
 *
 * @author jiachi
 */
class Registry {
    
    private static $instance=null;
    private $data=array();
    
    private function __construct() {
    }
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }
    
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function __get($name)
    {
        return $this->data[$name];
    }
    
}

?>
