<?php
namespace pqwe\Utils;

class Namespaces {
    public static function getFirst($class) {
        if ($class[0]=='\\')
            $class = substr($class, 1);
        if (($pos = strpos($class, '\\'))===false)
            return $class;
        return substr($class, 0, $pos);
    }
}

