<?php
namespace pqwe\Utils;

use pqwe\Exception\PqweException;

class Files {
    public static function makePath() {
        $arr = func_get_args();
        foreach($arr as &$p)
            if ($p[strlen($p)-1]==DIRECTORY_SEPARATOR)
                $p = substr($p, 0, strlen($p)-1);
        return join(DIRECTORY_SEPARATOR, $arr);
    }
    public static function prepareFragmented($filename, $basePath, $nPrefix=3, $newDirMode=0777) {
        $subfolder = substr($filename, 0, $nPrefix);
        $path = self::makePath($basePath, $subfolder);
        if (!is_dir($path))
            if (!mkdir($path, $newDirMode))
                throw new PqweException("could not create directory $path");
        return self::makePath($path, $filename);
    }
}

