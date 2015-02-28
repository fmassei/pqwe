<?php
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

class ControllerHTML extends ControllerBase {
    protected $layout;

    public function __construct($serviceManager) {
        parent::__construct($serviceManager);
    }
    public function preAction() {
        $this->layout = new View();
    }
    public function postAction(IView $view) {
        $this->layout->content = $view->return_output();
        $this->layout->render();
    }
}

