<?php
/**
 * RouterDefault class
 */
namespace pqwe\Routing;

use pqwe\Exception\PqweRoutingException;

/**
 * default class to parse the routes
 */
class RouterDefault implements IRouter {
    /**
     * @var \pqwe\ServiceManager\ServiceManager $serviceManager A ServiceManager
     * instance
     */
    protected $serviceManager;
    /**
     * @var array $def The array containing all the routes, typically taken from
     * the configuration held by the ServiceManager.
     */
    protected $def;
    /**
     * @var \pqwe\Routing\RoutesDefault $routes A Routes instance
     */
    protected $routes;

    /**
     * constructor
     *
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager A
     * ServiceManager instance
     * @param array $def The array containing all the routes, typically taken
     * from the configuration held by the ServiceManager.
     * @param \pqwe\Routing\RoutesDefault $routes A Routes instance
     */
    public function __construct($serviceManager, $def, $routes) {
        $this->serviceManager = $serviceManager;
        $this->def = $def;
        $this->routes = $routes;
    }

    /**
     * match the current URL to a route
     *
     * Returns a route matching the current URL, or throws an exception
     *
     * @param string $url The URL to match, empty for current
     * @return \pqwe\Routing\RouteMatch
     * @throws \pqwe\Exception\PqweRoutingException
     */
    public function match($url="") {
        if ($url!='')
            $cleanUrl = $url;
        else
            $cleanUrl = '/'.implode('/', $this->routes->getParts());
        foreach($this->def as $route) {
            switch ($route['type']) {
            case 'exact':
                if ($route['route']==$cleanUrl) {
                    $params = isset($route['params']) ? $route['params']
                                                      : array();
                    return new RouteMatch($route['controller'],
                                          $route['action'],
                                          $params);
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
                    if ($action===null && isset($route['action']))
                        $action = $route['action'];
                    if ($action===null)
                        throw new PqweRoutingException('no action');
                    return new RouteMatch($route['controller'],
                                          $action,
                                          $params);
                }
                break;
            case 'custom':
                $class = $route['class'];
                $router = new $class($this->serviceManager);
                if (($match = $router->match($cleanUrl))!==null)
                    return $match;
                break;
            }
        }
        throw new PqweRoutingException('no route for '.$cleanUrl);
    }
}

