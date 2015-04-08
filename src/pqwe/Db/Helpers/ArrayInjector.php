<?php
namespace pqwe\Db\Helpers;

class ArrayInjector {
    public static function toProperties($obj, $arr) {
        foreach ($arr as $key => $val)
            $obj->$key = $val;
    }
}

