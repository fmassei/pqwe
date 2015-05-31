<?php
/**
 * ControllerBase class
 */
namespace pqwe\Controller;

use pqwe\View\IView;

/**
 * Base class for controllers
 */
class ControllerBase {
    /** @var \pqwe\ServiceManager\ServiceManager $serviceManager A
     * ServiceManager instance */
    protected $serviceManager;

    /**
     * constructor
     *
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager A
     * ServiceManager instance
     */
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    /**
     * callback, called by the MVC object before the action method
     *
     * In derived classes the routeMatch parameter can be modified when we
     * want the MVC to change, for example, the action to be called (or some
     * of its parameters.
     *
     * @param \pqwe\Routing\RouteMatch $routeMatch The matched route.
     * @return void
     */
    public function preAction(&$routeMatch) { }

    /**
     * callback, called by the MVC object after the action method
     *
     * This base method calls the render() function of the View object.
     *
     * @param \pqwe\View\IView $view The View object returned by the action
     * method.
     * @param string $action Name of the called action
     * @return void
     */
    public function postAction(IView $view, $action) {
        $view->render();
    }

    /**
     * redirect to another page
     *
     * @param string $page The URL to redirect to.
     * @param int $code The HTTP response code to send to the client
     * @param string $schema The schema to use, null to use the current one
     */
    protected function redirect($page, $code=302, $schema=null) {
        $routes = $this->serviceManager->getOrGetDefault('pqwe_routes');
        $routes->redirect($page, $code, $schema);
    }
    /**
     * check is a $_POST[] entry is set and it's trim() is not empty
     *
     * @param string $name Name of the entry
     * @return bool
     */
    protected function isPOSTfilled($name) {
        return isset($_POST[$name]) && trim($_POST[$name])!="";
    }
    /**
     * returns trim($_POST[$name]) if the entry is set, or an empty string
     * otherwise
     *
     * @param string $name Name of the entry
     * @return string
     */
    protected function getPOSTstr($name) {
        return (!isset($_POST[$name]))?"":trim($_POST[$name]);
    }
    /**
     * returns trim($_POST[$name]) if the entry is set, or null otherwise
     *
     * @param string $name Name of the entry
     * @return null|string
     */
    protected function getPOSTnull($name) {
        $str = $this->getPOSTstr($name);
        return ($str=="") ? null : $str;
    }
    /**
     * returns $_POST[$name] if the entry is set, or $default otherwise
     *
     * @param string $name Name of the entry
     * @param mixed $default Value to return if the entry is not set
     * @return mixed
     */
    protected function getPOSTdefault($name, $default=null) {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }
    /**
     * for each passed argument, checks if the entry in $_POST is set, and if
     * not set a key with same name in the returning array
     *
     * For example: if our $_POST array containes the keys 'a', 'b' and 'c',
     * and we call getPOSTemptyArray('a','c','d'), the returned array will
     * be ('d'=>true).
     *
     * @param ... String(s) to be checked
     * @return array
     */
    protected function getPOSTemptyArray() {
        $ret = array();
        foreach(func_get_args() as $str)
            if (!$this->isPOSTfilled($str))
                $ret[$str] = true;
        return $ret;
    }

    /**
     * short-circuit the entire MVC flow and just return the passed string as an
     * attachment
     *
     * @param string $str The string to be sent
     * @param string $mime Mime-type of the attachment (in Content-Type)
     * @param string $filename Name of the file (in Content-Disposition)
     */
    protected function sendStringAttachment($str, $mime, $filename) {
        header("Content-Type: $mime");
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        header('Content-Transfer-Encoding: binary');
        echo $str;
        exit();
    }

    /**
     * short-circuit the entire MVC flow and just return the contents of the
     * passed file as an attachment
     *
     * @param string $file The file to be sent
     * @param string $mime Mime-type of the attachment (in Content-Type)
     * @param string $filename Name of the file (in Content-Disposition), or,
     * if null, the original filename.
     */
    protected function sendFileAttachment($file, $mime, $filename=null) {
        if ($filename===null)
            $filename = basename($file);
        $this->sendStringAttachment(file_get_contents($file),$mime,$filename);
    }
}

