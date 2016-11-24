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
     * @param mixed ... The path parts to be joined
     * @return string
     */
    public static function makePath() {
        $arr = func_get_args();
        for ($i=0; $i<count($arr)-1; ++$i) {
            if ($arr[$i]===null || $arr[$i]=="")
                continue;
            if (    $arr[$i][strlen($arr[$i])-1]!=DIRECTORY_SEPARATOR &&
                    $arr[$i+1][0]!=DIRECTORY_SEPARATOR)
                $arr[$i] .= DIRECTORY_SEPARATOR;
        }
        return implode('', $arr);
    }

    /**
     * fragment a filename, also creating the fragmented folder if not present
     *
     * When dealing with folders containing thousands of files, is normal to
     * add a second layer of folders to store the files into. This comes very
     * useful when the files are also named as an hash (e.g. MD5 or SHA).
     *
     * Returns the full path of the final file.
     *
     * Example: the file 'abcde.txt' will be stored in the 'abc' folder.
     *
     * @param string $filename The filename to fragment
     * @param string $basePath The basepath to add to the fragment
     * @param int $nPrefix Number of characters to take for the
     * fragmentation
     * @param int $newDirMode Permission to pass to mkdir when creating
     * a new folder
     * @throws PqweException
     * @return string
     */
    public static function prepareFragmented($filename, $basePath, $nPrefix=3, $newDirMode=0777) {
        return self::prepareFragmentedMulti($filename, $basePath, 1, $nPrefix, $newDirMode);
    }

    /**
     * fragment a filename, also creating the fragmented folder tree if some
     * subfolders are not present.
     *
     * When dealing with folders containing thousands of files, is normal to
     * add more layers of folders to store the files into. This comes very
     * handy when the files are also named as an hash (e.g. MD5 or SHA).
     *
     * Returns the full path of the final file.
     *
     * Example: the file 'abcdef0123.txt', with two levels and a tree letter
     * prefix, will be stored in the 'abc/def/' folder.
     *
     * @param string $filename The filename to fragment
     * @param string $basePath The basepath to add to the fragment
     * @param int $nLevels Number of layers
     * @param int $nPrefix Number of characters to take for the
     * fragmentation
     * @param int $newDirMode Permission to pass to mkdir when creating
     * a new folder
     * @throws PqweException
     * @return string
     */
    public static function prepareFragmentedMulti($filename, $basePath, $nLevels=2, $nPrefix=3, $newDirMode=0777) {
        if (strlen($filename)<$nPrefix*$nLevels)
            throw new PqweException("filename shorter than prefix");
        $path = $basePath;
        for ($i=0; $i<$nLevels; ++$i) {
            $subfolder = substr($filename, $i*$nPrefix, $nPrefix);
            $path = self::makePath($path, $subfolder);
        }
        if (!is_dir($path))
            if (!mkdir($path, $newDirMode, true))
                throw new PqweException("could not create directory $path");
        return self::makePath($path, $filename);
    }
}

