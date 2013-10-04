<?php

include_once 'BaseAction.class.php';

/**
 * Description of ErrorAction
 *
 * @author jiachi
 */
class ErrorAction extends BaseAction{
    
    private $errorMsg;
    private $backUrl="index.php";
    
    public function setErrorMsg($errorMsg){
        $this->errorMsg=$errorMsg;
    }
    
    public function setBackUrl($back){
        $this->backUrl = $back;
    }
    
    public function execute() {
        $template = $this->getTemplate();
        $template -> errorMsg = $this->errorMsg;
        $template -> backUrl = $this->backUrl;
        $template ->show("error");
    }    
}

?>
