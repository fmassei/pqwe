<?php
/**
 * Files class
 */
namespace pqwe\Utils;

use pqwe\Exception\PqweException;

/**
 * class with some helpers to deal with filenames
 */
class Files {
    /**
     * makes a path out of the parts passed
     *
     * @static
     * @param mixed ... The path parts to be joined
     * @return string
     */
    public static function makePath() {
        $arr = func_get_args();
        foreach($arr as &$p)
            if ($p[strlen($p)-1]==DIRECTORY_SEPARATOR)
                $p = substr($p, 0, strlen($p)-1);
        return join(DIRECTORY_SEPARATOR, $arr);
    }

    /**
     * fragment a filename, also creating the fragmented folder if not present
     *
     * When dealing with folders containing thousands of files, is normal to
     * add a second layer of folders to store the files into. This comes very
     * useful when the files are also named as an hash (e.g. MD5 or SHA).
     *
     * Example: the file 'abcde.txt' will be stored in the 'abc' folder.
     *
     * @param string $filename The filename to fragment
     * @param string $basePath The basepath to add to the fragment
     * @param int $nPrefix Number of characters to take for the
     * fragmentation
     * @param int $newDirMode Permission to pass to mkdir when creating
     * a new folder
     * @return string
     */
    public static function prepareFragmented($filename, $basePath, $nPrefix=3, $newDirMode=0777) {
        $subfolder = substr($filename, 0, $nPrefix);
        $path = self::makePath($basePath, $subfolder);
        if (!is_dir($path))
            if (!mkdir($path, $newDirMode))
                throw new PqweException("could not create directory $path");
        return self::makePath($path, $filename);
    }
}

