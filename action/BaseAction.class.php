<?php

include_once 'ErrorAction.class.php';

/**
 * Description of BaseAction
 *
 * @author jiachi
 */
abstract class BaseAction {

    protected $registry;

    public function setRegistry($registry) {
        $this->registry = $registry;
    }

    public function getTemplate() {
        return $this->registry->template;
    }

    public function error($errorMsg, $backUrl="index.php") {
        $errorAction = new ErrorAction();
        $errorAction->setRegistry($this->registry);
        $errorAction->setBackUrl($backUrl);
        $errorAction->setErrorMsg($errorMsg);
        $errorAction->execute();
    }

    public abstract function execute();
}

?>
