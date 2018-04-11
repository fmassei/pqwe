<?php
/**
 * IRouter interface
 */
namespace pqwe\Routing;

use pqwe\Exception\PqweMVCException;
use pqwe\Exception\PqweRoutingException;

/**
 * interface to be implemented by routers
 */
interface IRouter {
    /**
     * match the passed url
     *
     * @param string $url The url to match
     * @return RouteMatch
     * @throws PqweMVCException
     * @throws PqweRoutingException
     */
    public function match($url="");
}

