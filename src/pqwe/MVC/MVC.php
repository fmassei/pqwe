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
        $controllerClass = $routeMatch->controller;
        $controller = new $controllerClass($this->serviceManager);
        $controller->preAction($routeMatch);
        $view = call_user_func_array(array($controller,
                                           $routeMatch->action.'Action'),
                                     $routeMatch->params);
        $controller->postAction($view, $routeMatch->action);
    }
}

