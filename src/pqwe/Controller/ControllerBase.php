<?php
namespace pqwe\Controller;

use pqwe\View\IView;

class ControllerBase {
    protected $serviceManager;
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    public function preAction(&$routeMatch) { }
    public function postAction(IView $view, $action) {
        $view->render();
    }

    protected function redirect($page, $code=302, $schema=null) {
        $routes = $this->serviceManager->getOrGetDefault('pqwe_routes');
        $routes->redirect($page, $code, $schema);
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
    protected function sendStringAttachment($str, $mime, $filename) {
        header("Content-type: $mime");
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        header('Content-Transfer-Encoding: binary');
        echo $str;
        exit();
    }
    protected function sendFileAttachment($file, $mime, $filename=null) {
        if ($filename===null)
            $filename = basename($file);
        $this->sendStringAttachment(file_get_contents($file),$mime,$filename);
    }
}

