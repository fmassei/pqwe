<?php
namespace pqwe\MVC;

class MVC {
    protected $serviceManager;

    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    public function run() {
        if (!$this->serviceManager->isRegistered('pqwe_routes')) {
            $routes = new \pqwe\Routing\RoutesDefault();
            $this->serviceManager->set('pqwe_routes', $routes);
        } else {
            $routes = $this->serviceManager->get('pqwe_routes');
        }
        if (!$this->serviceManager->isRegistered('pqwe_router')) {
            $config = $this->serviceManager->get('config');
            $router = new \pqwe\Routing\RouterDefault($this->serviceManager, $config['routes'], $routes);
            $this->serviceManager->set('pqwe_router', $router);
        } else {
            $router = $this->serviceManager->get('pqwe_router');
        }
        $what = $router->match();
        $controller = new $what['controller']($this->serviceManager);
        $action = $what['action'];
        $controller->preAction($what);
        $view = call_user_func_array(array($controller, $action.'Action'),
                                     $what['params']);
        $controller->postAction($view, $action);
    }
}

