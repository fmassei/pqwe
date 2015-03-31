<?php
namespace pqwe\Routing;

class RouteMatch {
    public $controller;
    public $action;
    public $params;
    public function __construct($controller, $action, $params) {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
}

