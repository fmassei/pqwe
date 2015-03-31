<?php
namespace pqwe\MVC;

class MVC {
    protected $serviceManager;

    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    public function run() {
        $router = $this->serviceManager->getOrGetDefault('pqwe_router');
        $routeMatch = $router->match();
        $controller = $routeMatch->controller;
        $controller = new $controller($this->serviceManager);
        $action = $routeMatch->action;
        $controller->preAction($routeMatch);
        $view = call_user_func_array(array($controller, $action.'Action'),
                                     $routeMatch->params);
        $controller->postAction($view, $action);
    }
}

