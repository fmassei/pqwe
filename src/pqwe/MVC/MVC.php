<?php
/**
 * MVC main class
 */
namespace pqwe\MVC;

use pqwe\Exception\PqweACLException;
use pqwe\Exception\PqweMVCException;

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
     * Check, if ACL is on, if the route is allowed for the role
     *
     * @param \pqwe\Routing\RouteMatch $routeMatch Route to check
     * @param string $acl_role Role to check
     * @return void
     */
    protected function checkAuth(&$routeMatch, $acl_role) {
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
        if ($acl_role===null)
            $acl_role = $acl->getDefaultRoleName();
        if (!$acl->isAllowed($acl_role, $resource, $privilege)) {
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
     * @param string $acl_role If set, and if using ACLs, check with this role.
     * @return void
     */
    public function run($acl_role=null) {
        $router = $this->serviceManager->getOrGetDefault('pqwe_router');
        $routeMatch = $router->match();
        $this->checkAuth($routeMatch, $acl_role);
        do {
            $controllerClass = $routeMatch->controller;
            $controller = new $controllerClass($this->serviceManager);
            $controller->preAction($routeMatch);
        } while ($routeMatch->controller!=$controllerClass);
        $method = $routeMatch->action.'Action';
        if (!method_exists($controller, $method))
            throw new PqweMVCException("method $method doesn't exist in class $controllerClass");
        $view = call_user_func_array(array($controller,
                                           $method),
                                     $routeMatch->params);
        $controller->postAction($view, $routeMatch->action);
    }
}

