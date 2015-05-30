<?php
/**
 * PqweException class
 */
namespace pqwe\Exception;

/**
 * pqwe base exception
 *
 * @exception
 */
class PqweException extends \Exception {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

