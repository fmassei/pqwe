<?php
/**
 * IRouter interface
 */
namespace pqwe\Routing;

/**
 * interface to be implemented by routers
 */
interface IRouter {
    /**
     * match the passed url
     *
     * @param string $url The url to match
     * @return \pqwe\Routing\RouteMatch
     */
    public function match($url);
}

