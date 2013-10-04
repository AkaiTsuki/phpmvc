<?php

include_once 'BaseAction.class.php';

/**
 * Description of IndexAction
 *
 * @author jiachiliu
 */
class IndexAction extends BaseAction{
    public function execute() {
        $tmp = $this->getTemplate();
        $tmp->message = "Hello World";
        $tmp->show("home");
    }    
}

?>
