<?php
/**
 * MVC main class
 */
namespace pqwe\MVC;

use pqwe\Controller\ControllerBase;
use pqwe\Exception\PqweACLException;
use pqwe\Exception\PqweMVCException;
use pqwe\Exception\PqweRoutingException;
use pqwe\Exception\PqweServiceManagerException;
use pqwe\Routing\IRouter;
use pqwe\Routing\RouteMatch;

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
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager
     */
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Check, if ACL is on, if the route is allowed for the role
     *
     * @param RouteMatch $routeMatch Route to check
     * @param string|array $acl_roles Role(s) to check
     * @return void
     * @throws PqweACLException
     * @throws PqweServiceManagerException
     */
    protected function checkAuth(&$routeMatch, $acl_roles) {
        if (!isset($routeMatch->rawRoute['resource']))
            return;
        $config = $this->serviceManager->get('config');
        if (!isset($config['acl']))
            return;
        $resource = $routeMatch->rawRoute['resource'];
        $privilege = null;
        if (isset($routeMatch->rawRoute['privilege']))
            $privilege = $routeMatch->rawRoute['privilege'];
        $acl = $this->serviceManager->getOrGetDefault('pqwe_acl');
        if ($acl_roles===null)
            $acl_roles = $acl->getDefaultRoleName();
        if (!$acl->isAllowed($acl_roles, $resource, $privilege)) {
            if (isset($config['acl']['unauthorized'])) {
                $routeMatch->controller = $config['acl']['unauthorized']['controller'];
                $routeMatch->action = $config['acl']['unauthorized']['action'];
            } else {
                throw new PqweACLException('unauthorized');
            }
        }
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
     * @param string|array $acl_role If set, and if using ACLs, check with this
     *  role or array of roles.
     * @return void
     * @throws PqweMVCException
     * @throws PqweACLException
     * @throws PqweServiceManagerException
     * @throws PqweRoutingException
     */
    public function run($acl_role=null) {
        /** @var IRouter $router */
        $router = $this->serviceManager->getOrGetDefault('pqwe_router');
        $routeMatch = $router->match();
        $this->runRouteMatch($routeMatch, $acl_role);
    }

    /**
     * Run a route match
     *
     * This function runs a low-level route match
     *
     * @param RouteMatch $routeMatch
     * @param string|array $acl_role If set, and if using ACLs, check with this
     *  role or array of roles.
     * @return void
     * @throws PqweMVCException
     * @throws PqweACLException
     * @throws PqweServiceManagerException
     */
    public function runRouteMatch($routeMatch, $acl_role=null) {
        $this->checkAuth($routeMatch, $acl_role);
        do {
            $controllerClass = $routeMatch->controller;
            /** @var ControllerBase $controller */
            $controller = new $controllerClass($this->serviceManager);
            $controller->preAction($routeMatch);
        } while ($routeMatch->controller!=$controllerClass);
        $method = $routeMatch->action;
        if (!method_exists($controller, $method))
            throw new PqweMVCException("method $method doesn't exist in class $controllerClass");
        $view = call_user_func_array(array($controller,
                                           $method),
                                     $routeMatch->params);
        $controller->postAction($view, $routeMatch->action);
    }

    /**
     * Manually run a controller's action
     *
     * @param string $controllerName Name of the controller class
     * @param string actionName Name of the action
     * @return void
     * @throws PqweMVCException
     * @throws PqweACLException
     * @throws PqweServiceManagerException
     */
    public function runControllerAction($controllerName, $actionName) {
        $routeMatch = new RouteMatch($controllerName, $actionName, array());
        $this->runRouteMatch($routeMatch);
    }
}

