<?php
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

class ControllerHTML extends ControllerBase {
    protected $layout;

    public function __construct($serviceManager) {
        parent::__construct($serviceManager);
    }
    public function preAction($routeMatch) {
        $this->layout = new View();
        $namespace = substr($routeMatch['controller'], 0,
            strpos($routeMatch['controller'], '\\', 1));
        if ($namespace[0]=='\\')
            $namespace = substr($namespace, 1);
        $this->layout->setViewFile($namespace.'/view/layout/layout.phtml');

    }
    public function postAction(IView $view) {
        $this->layout->content = $view->return_output();
        $this->layout->render();
    }
}

