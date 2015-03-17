<?php
namespace pqwe\MVC;

class MVC {
    protected $serviceManager;

    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    public function run() {
        $router = $this->serviceManager->getOrGetDefault('pqwe_router');
        $what = $router->match();
        $controller = new $what['controller']($this->serviceManager);
        $action = $what['action'];
        $controller->preAction($what);
        $view = call_user_func_array(array($controller, $action.'Action'),
                                     $what['params']);
        $controller->postAction($view, $action);
    }
}

