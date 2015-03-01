<?php
namespace pqwe\Exception;

class PqweDbException extends PqweException {
    public function __construct($str) {
        parent::__construct($str);
    }
}

