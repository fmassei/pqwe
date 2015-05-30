<?php
/**
 * PqweMVCException class
 */
namespace pqwe\Exception;

/**
 * exception for MVC classes
 *
 * @exception
 */
class PqweMVCException extends PqweException {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

