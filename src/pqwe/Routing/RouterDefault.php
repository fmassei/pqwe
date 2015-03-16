<?php
namespace pqwe\Routing;

use pqwe\Exception\PqweRoutingException;

class RouterDefault {
    protected $serviceManager;
    protected $def;
    protected $routes;
    public function __construct($serviceManager, $def, $routes) {
        $this->serviceManager = $serviceManager;
        $this->def = $def;
        $this->routes = $routes;
    }
    public function match() {
        $cleanUrl = '/'.implode('/', $this->routes->getParts());
        foreach($this->def as $route) {
            switch ($route['type']) {
            case 'exact':
                if ($route['route']==$cleanUrl) {
                    $params = isset($route['params']) ? $route['params']
                                                      : array();
                    return array('controller' => $route['controller'],
                                 'action' => $route['action'],
                                 'params' => $params);
                }
                break;
            case 'regexp':
                $matches = array();
                if (preg_match($route['route'], $cleanUrl, $matches,
                               PREG_OFFSET_CAPTURE)) {
                    $action = null;
                    $params = array();
                    for($i=0; $i<count($route['matches']); ++$i)
                        if ($route['matches'][$i]=='action')
                            $action = $matches[$i+1][0];
                        else
                            $params[] = $matches[$i+1][0];
                    if ($action===null)
                        throw new PqweRoutingException('no action');
                    return array('controller' => $route['controller'],
                                 'action' => $action,
                                 'params' => $params);
                }
                break;
            }
        }
        throw new PqweRoutingException('no route for '.$cleanUrl);
    }
}
