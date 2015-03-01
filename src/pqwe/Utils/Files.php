<?php
namespace pqwe\Utils;

class Files {
    public static function makePath() {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }
}

