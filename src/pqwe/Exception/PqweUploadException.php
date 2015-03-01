<?php
namespace pqwe\Exception;

class PqweUploadException extends PqweException {
    public function __construct($str) {
        parent::__construct($str);
    }
}

