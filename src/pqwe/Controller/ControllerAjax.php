<?php
/**
 * ControllerAjax class
 */
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

/**
 * Controller specialized for AJAX actions
 * @todo document this thing
 */
class ControllerAjax extends ControllerBase {
    /**
     * constructor
     *
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager A
     * ServiceManager instance
     */
    public function __construct($serviceManager) {
        parent::__construct($serviceManager);
    }

    /**
     * generic action method. It creates a View and fills it with the execute()
     * return value (or with an error response, in case of errors).
     *
     * @return \pqwe\View\View
     */
    public function ajaxAction() {
        $view = new View();
        try {
            $view->setContent($this->execute());
        } catch(\Exception $ex) {
            $view->setContent($this->response(false,
                                          array('error'=>$ex->getMessage())));
        }
        return $view;
    }

    /**
     * execute an ajax action, taking the $_POST['action'] value and calling
     * the appropriate method.
     *
     * @return string
     */
    protected function execute() {
        if (($ajaxAction = $this->getPOSTdefault('action'))===null)
            throw new \Exception('invalid action');
        $method = $ajaxAction.'AjaxAction';
        if (!method_exists($this, $method))
            throw new \Exception("method $method not present");
        $response = call_user_func(array($this, $method));
        return $this->response(true, $response);
    }

    /**
     * build a proper response, returning a JSON string
     *
     * @param bool $success If we managed to fullfill the request
     * @param string $response The response content
     * @return string
     */
    protected function response($success, $response) {
        return json_encode(array('requestSuccess' => $success,
                                 'response' => $response));
    }
}

