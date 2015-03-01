<?php
namespace pqwe\Exception;

class PqweRoutingException extends PqweException {
    public function __construct($str) {
        parent::__construct($str);
    }
}

