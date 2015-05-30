<?php
/**
 * PqweUploadException class
 */
namespace pqwe\Exception;

/**
 * exception for upload 
 *
 * @exception
 */
class PqweUploadException extends PqweException {
    /**
     * constructor
     *
     * @param string $str Exception message
     */
    public function __construct($str) {
        parent::__construct($str);
    }
}

