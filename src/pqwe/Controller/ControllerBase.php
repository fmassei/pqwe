<?php
namespace pqwe\Controller;

use pqwe\View\IView;

class ControllerBase {
    protected $serviceManager;
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    public function preAction($routeMatch) { }
    public function postAction(IView $view, $action) {
        $view->render();
    }

    protected function redirect($page, $code=302) {
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location: http://$host$uri/$page", true, $code);
        die();
    }
    protected function isPOSTfilled($name) {
        return isset($_POST[$name]) && trim($_POST[$name])!="";
    }
    protected function getPOSTstr($name) {
        return (!isset($_POST[$name]))?"":trim($_POST[$name]);
    }
    protected function getPOSTnull($name) {
        $str = $this->getPOSTstr($name);
        return ($str=="") ? null : $str;
    }
    protected function getPOSTdefault($name, $default=null) {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }
    protected function getPOSTemptyArray() {
        $ret = array();
        foreach(func_get_args() as $str)
            if (!$this->isPOSTfilled($str))
                $ret[$str] = true;
        return $ret;
    }
}
