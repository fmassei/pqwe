<?php
/**
 * Namespaces class
 */
namespace pqwe\Utils;

/**
 * Namespaces class
 *
 * Thought for internal use only, helps with namespace mangling
 */
class Namespaces {
    /**
     * returns the first namespace of a namespace chain
     *
     * @param string $class The class name with namespaces
     * @return string
     */
    public static function getFirst($class) {
        if ($class[0]=='\\')
            $class = substr($class, 1);
        if (($pos = strpos($class, '\\'))===false)
            return $class;
        return substr($class, 0, $pos);
    }
}

