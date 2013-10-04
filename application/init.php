<?php
include SITE_DIR_PATH.'/application/config.php';
include SITE_DIR_PATH.'/application/Registry.class.php';
include SITE_DIR_PATH.'/application/Template.class.php';
include SITE_DIR_PATH.'/application/FrontController.class.php';
include SITE_DIR_PATH.'/application/ActionFactory.class.php';

/* Automatically include class definition file for modules like domain object */
function __autoload($classname){
    $filename = $classname.".class.php";
    $path = SITE_DIR_PATH."/module/".$filename;
    
    if (!file_exists($path)){
        die("Cannot find requested action definition: ".$filename);
    }
    
    include $path;
}

function htmlout($content){
    echo htmlspecialchars(trim($content));
}

?>
