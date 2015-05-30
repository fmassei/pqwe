<?php
/**
 * PqweServiceManagerException class
 */
namespace pqwe\Exception;

/**
 * exception for ServiceManager classes
 *
 * @exception
 */
class PqweServiceManagerException extends PqweException {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

