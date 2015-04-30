<?php
namespace pqwe\Utils;

class Files {
    public static function makePath() {
        $arr = func_get_args();
        foreach($arr as &$p)
            if ($p[strlen($p)-1]==DIRECTORY_SEPARATOR)
                $p = substr($p, 0, strlen($p)-1);
        return join(DIRECTORY_SEPARATOR, $arr);
    }
}

