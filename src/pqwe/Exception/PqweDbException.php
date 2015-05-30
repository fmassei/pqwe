<?php
/**
 * PqweDbException class
 */
namespace pqwe\Exception;

/**
 * exception for database classes
 *
 * @exception
 */
class PqweDbException extends PqweException {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

