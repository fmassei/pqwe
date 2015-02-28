<?php
namespace pqwe\Routing;

class RoutesDefault {
    protected $cached_parts = null;
    protected $cached_host = null;

    public function getURLParts($url) {
        if (($qs = strpos($url, "?"))!==false)
            $url = substr($url, 0, $qs);
        $uriParts = explode("/", $url);
        array_shift($uriParts);
        if (count($uriParts)>=1 && $uriParts[count($uriParts)-1]==="")
            array_pop($uriParts);
        return $uriParts;
    }
    public function getParts() {
        if ($this->cached_parts===null)
            $this->cached_parts = $this->getURLParts($_SERVER['REQUEST_URI']);
        return $this->cached_parts;
    }
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
            $cached_host = trim($host);
        }
        return $this->cached_host;
    }
}

