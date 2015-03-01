<?php
namespace pqwe\Exception;

class PqweMVCException extends PqweException {
    public function __construct($str) {
        parent::__construct($str);
    }
}

