<?php
/**
 * ArrayInjector class
 */
namespace pqwe\Db\Helpers;

/**
 * Inject properties inside an object
 *
 * Note: when possible, just use a simple cast
 *
 *      $obj = (object)$array
 *
 */
class ArrayInjector {
    /**
     * Inject properties inside an object
     *
     * @param mixed $obj The target object
     * @param array $arr The source array
     * @return void
     */
    public static function toProperties($obj, $arr) {
        foreach ($arr as $key => $val)
            $obj->$key = $val;
    }
}


