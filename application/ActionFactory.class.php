<?php

/**
 * ActionFactory is response for create Action instance based on the 
 * given name.
 * Only one factory is permitted in the website
 *
 * @author jiachi
 */
class ActionFactory {
    
    //Singleton Pattern instance
    private static $instance;
    
    //Global variable storage
    private $registry;
    
    private function __construct($registry) {
        $this->registry = $registry;
    }
    
    /*
     * Get instance of factory, if it is not exists, create a new one and return
     */
    public static function getInstance($registry){
        if(!isset(self::$instance))
            self::$instance = new self($registry);
        return self::$instance;
    }

    /*
     * Given the action name,
     * Return an action instance based on the name.
     * If the action is not found, return null
     */
    public function getAction($name){
        $action = $name."Action";
        $fileName = $action.".class.php";
        $path = SITE_DIR_PATH.'/action/'. $fileName;
        if (is_readable($path)) {
            include_once $path;
            $class = new ReflectionClass($action);
            $actionObj = $class->newInstance();
            $actionObj->setRegistry($this->registry);
            return $actionObj;
        }
        return null;
    }
}

?>
