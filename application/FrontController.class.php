<?php

/**
 * Front Controller receives all request sent from client and 
 * route to the relative action based on the action and method field in
 * query string in url.
 *
 * @author jiachi
 */
class FrontController {
    
    // The key of action name in url query string
    const REQUEST_KEY = 'a';
    // The key of method name in url query string
    const METHOD_KEY = 'm';
    // Global variable storage object
    private $registry;
    // Action factory
    private $actionFactory;
    
    public function __construct($actionFactory,$registry) {
        $this->actionFactory = $actionFactory;
        $this->registry=$registry;
    }
    
    /*
     * Process the request from client by dynamically creating the instance
     * of action object based on the url action field and invoke its 
     * execute method.
     * If an action is not found then try to redirect to the page in the same name
     * If still fail, process to the error action. 
     * 
     * If the action is found, then try to get the method field in query string
     * and test if the method is callable in action class, if it is, invoke the
     * method, else invoke the execute method.
     */
    public function process(){
        $actionName = 'Index';
        
        if(isset($_REQUEST[self::REQUEST_KEY])){
           $actionName = $_REQUEST[self::REQUEST_KEY]; 
        }
        
        
        
        $action = $this->actionFactory->getAction(ucfirst($actionName));
        
        if($action === null){
            /* If action is not found, try to find page */
            $page = $actionName.".php";
            $pagepath = SITE_DIR_PATH."/".$page;
            
            if(is_readable($pagepath)){
                header("Location: ".SITE_ROOT_URL."/".$page);
            }         
            else{
                $action = $this->actionFactory->getAction("Error");    
                $action -> setErrorMsg("Error 404, the page you request cannot be found.");
                $action->execute();
            }
        }else{
            /* If the method field is not set */
            if(!isset($_REQUEST[self::METHOD_KEY])){
                $action->execute();
             }else{
                 $methodName = $_REQUEST[self::METHOD_KEY];
                 /* Test whether the given method is callable */
                 if (!is_callable(array($action,$methodName))) {
                     $action->execute();
                 }else{
                     $action->{$methodName}();
                 }
             }
        }        
    }
}

?>
