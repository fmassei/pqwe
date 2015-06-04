<?php
/**
 * PqweACLException class
 */
namespace pqwe\Exception;

/**
 * exception for ACL services
 *
 * @exception
 */
class PqweACLException extends PqweException {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

