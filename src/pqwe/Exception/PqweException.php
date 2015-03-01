<?php
namespace pqwe\Exception;

class PqweException extends \Exception {
    public function __construct($str) {
        parent::__construct($str);
    }
}

