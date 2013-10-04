<?php
/*
 * The index.php serves as a central controller(front controller) that all 
 * request will be sent to this page. The front controller will re-send the 
 * request to related actions based on the query string in url which contains
 * the following two parts:
 * @param a: 
 *          the name of the action. Example: index.php?a=login
 *          In this case, the controller will find the class definition file
 *          in name of LoginAction.class.php.
 * @param m[option]: 
 *          the name of the method will be called. Continue the previous example,
 *          if we also has a query string that index.php?a=login&m=test
 *          Then the controller will try to invoke the test method in LoginAction
 *          class and if it is failed, the controller will invoke the execute
 *          method.
 */
session_start();
error_reporting(E_ALL);
$site_dir_path = realpath(dirname(__FILE__));
define('SITE_DIR_PATH', $site_dir_path);
define("SITE_ROOT_URL", "http://localhost:8888/phpmvc");

include SITE_DIR_PATH.'/application/init.php';

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

$registry = Registry::getInstance();
$template = new Template($registry);
$registry -> template = $template;
$actionFactory =  ActionFactory::getInstance($registry);

$controller = new FrontController($actionFactory,$registry);
$controller->process();

?>
