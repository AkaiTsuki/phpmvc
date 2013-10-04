<?php


/**
 * Template stores the data that will be displayed in htm template page
 * When show() method is called, the template will inject those data into
 * destination page.
 *
 * @author jiachi
 */
class Template {
    
    private $registry;
    private $data=array();
    
    public function __construct($registry) {
        $this->registry=$registry;
    }
    
    public function __set($name, $value) {
        $this->data[$name]=$value;
    }
    
    public function show($name){
        $path=SITE_DIR_PATH."/view/".$name.'.htm.php';
        if(!file_exists($path)){
            die("Cannot find template: ".$path);
        }
        
        foreach($this->data as $key=>$value){
            $$key=$value;
        }
        
        include $path;
    }
}

?>
