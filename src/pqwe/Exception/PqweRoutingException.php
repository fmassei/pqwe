<?php
/**
 * PqweRoutingException class
 */
namespace pqwe\Exception;

/**
 * exception for routing classes
 *
 * @exception
 */
class PqweRoutingException extends PqweException {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

