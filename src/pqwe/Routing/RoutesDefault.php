<?php
/**
 * RoutesDefault class
 */
namespace pqwe\Routing;

/**
 * Class dealing with URLs, hosts and schema
 */
class RoutesDefault {
    /** cached URL parts */
    protected $cached_parts = null;
    /** cached host */
    protected $cached_host = null;
    /** cached schema */
    protected $cached_schema = null;

    /**
     * split the passed URL in parts, after removing the hostname
     *
     * @param string $url The URL to split
     * @return array 
     */
    public function getURLParts($url) {
        if (($qs = strpos($url, "?"))!==false)
            $url = substr($url, 0, $qs);
        $uriParts = explode("/", $url);
        array_shift($uriParts);
        if (count($uriParts)>=1 && $uriParts[count($uriParts)-1]==="")
            array_pop($uriParts);
        return $uriParts;
    }

    /**
     * get the parts of the current URL, calling getURLParts
     * @return array
     */
    public function getParts() {
        if ($this->cached_parts===null)
            $this->cached_parts = $this->getURLParts($_SERVER['REQUEST_URI']);
        return $this->cached_parts;
    }

    /**
     * get the hostname
     *
     * This function checks for various server variables, returning the hostname
     * (without port number)
     *
     * @return string
     */
    public function getHost() {
        if ($this->cached_host===null) {
            if (    isset($_SERVER['HTTP_X_FORWARDED_HOST']) &&
                    $host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
                $elements = explode(',', $host);
                $host = trim(end($elements));
            } else {
                if (!$host = $_SERVER['HTTP_HOST'])
                    if (!$host = $_SERVER['SERVER_NAME'])
                        $host = !empty($_SERVER['SERVER_ADDR'])
                                ? $_SERVER['SERVER_ADDR']
                                : '';
            }
            // Remove port number from host
            $host = preg_replace('/:\d+$/', '', $host);
            $this->cached_host = trim($host);
        }
        return $this->cached_host;
    }
    /**
     * returns the current schema
     *
     * this will hopefully work even under a load balancer/reverse proxy
     *
     * @return string
     */
    public function getSchema() {
        if ($this->cached_schema===null) {
            $isHTTPS = false;
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') {
                $isHTTPS = true;
            } else if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                       $_SERVER['HTTP_X_FORWARDED_PROTO']=='https' ||
                       !empty($_SERVER['HTTP_X_FORWARDED_SSL']) &&
                       $_SERVER['HTTP_X_FORWARDED_SSL']!=='off') {
                $isHTTPS = true;
            }
            $this->cached_schema = $isHTTPS ? 'https' : 'http';
        }
        return $this->cached_schema;
    }

    /**
     * low-level redirection to another page
     *
     * If in a controller, use the controller member instead.
     * @param string $page The URL to redirect to.
     * @param int $code The HTTP response code to send to the client
     * @param string $schema The schema to use, null to use the current one
     */
    public function redirect($page, $code=302, $schema=null) {
        if ($schema===null)
            $schema = $this->getSchema();
        $host = $this->getHost();
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        if ($page=="" || $page[0]!='/')
            $page = '/'.$page;
        header("Location: $schema://$host$uri$page", true, $code);
        die();
    }
}

