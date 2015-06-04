<?php
/**
 * RouteMatch class
 */

namespace pqwe\Routing;

/**
 * The object abstracting a route match
 */
class RouteMatch {
    /** @var string $controller The name of the controller class */
    public $controller;
    /** @var string $action The name of the controller action method to call */
    public $action;
    /** @var array $params Extra parameters to pass to the action method */
    public $params;
    /** @var array $rawRoute The array as is in the configuration */
    public $rawRoute;

    /**
     * constructor
     *
     * @param string $controller The name of the controller class
     * @param string $action The name of the controller action method to call
     * @param array $params Extra parameters to pass to the action method
     * @param array $rawRoute The array as is in the configuration, if any
     */
    public function __construct($controller, $action, $params, $rawRoute=null) {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
        $this->rawRoute = $rawRoute;
    }
}

