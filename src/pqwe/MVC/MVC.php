<?php
/**
 * MVC main class
 */
namespace pqwe\MVC;

/**
 * Main MVC class
 */
class MVC {
    /**
     * The ServiceManager instance
     */
    protected $serviceManager;

    /**
     * constructor
     *
     * @param ServiceManager $serviceManager The ServiceManager instance
     */
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Run the MVC process
     * 
     * This function uses the transparent "pqwe_router" object, taking the
     * default one if the user doesn't specify one in the serviceManager.
     *
     * It asks the router for a match, creates a controller and calls, in
     * order, its "preAction()" method, the action method associated with the
     * route, and finally its "postAction()" method.
     * 
     * @return void
     */
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

