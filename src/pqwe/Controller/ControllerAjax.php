<?php
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

class ControllerAjax extends ControllerBase {
    public function __construct($serviceManager) {
        parent::__construct($serviceManager);
    }
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
    protected function execute() {
        if (($ajaxAction = $this->getPOSTdefault('action'))===null)
            throw new \Exception('invalid action');
        $method = $ajaxAction.'AjaxAction';
        if (!method_exists($this, $method))
            throw new \Exception("method $method not present");
        $response = call_user_func(array($this, $method));
        return $this->response(true, $response);
    }
    protected function response($success, $response) {
        return json_encode(array('requestSuccess' => $success,
                                 'response' => $response));
    }
}

