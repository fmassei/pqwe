<?php
/**
 * FileUpload class
 */
namespace pqwe\Form;

use \pqwe\Exception\PqweUploadException;

/**
 * FileUpload class
 *
 * static class that helps dealing with file uploads.
 */
class FileUpload {
    /**
     * getFileObj
     *
     * Takes a $_FILES entry and perform various checks, like if there was
     * an error during the transfer, if the mime type is correct, and so on.
     *
     * @static
     * @param string $fieldName The key of the $_FILES array
     * @param int $maxSize Maximum file size, 0 for no limit
     * @param array $acceptedMimeTypes Array of mime types to accept,
     * null for no checks
     * @return array The raw $_FILES entry
     * @throws PqweUploadException
     */
    public static function getFileObj($fieldName, $maxSize=0,
                                      $acceptedMimeTypes=null)
    {
        $file = $_FILES[$fieldName];
        if (!isset($file['error']) || is_array($file['error']))
            throw new PqweUploadException('Invalid parameters.');
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new PqweUploadException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new PqweUploadException('Exceeded filesize limit [machine configuration].');
            default:
                throw new PqweUploadException('Unknown errors.');
        }
        if ($maxSize>0 && $file['size']>$maxSize)
            throw new PqweUploadException('Exceeded filesize limit [site coded].');
        if ($acceptedMimeTypes!==null) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (($ext = array_search(
                    $finfo->file($file['tmp_name']),
                    $acceptedMimeTypes, true))===false)
            throw new PqweUploadException('Invalid file format.');
        }
        return $file;
    }
    /**
     * getFileObjIndexed
     *
     * Takes a $_FILES entry and perform various checks, like if there was
     * an error during the transfer, if the mime type is correct, and so on.
     * Multifile-upload version.
     *
     * @static
     * @param string $fieldName The key of the $_FILES array
     * @param int $idx The index inside the $_FILES arrays
     * @param int $maxSize Maximum file size, 0 for no limit
     * @param array $acceptedMimeTypes Array of mime types to accept,
     * null for no checks
     * @return array The raw $_FILES entry
     * @throws PqweUploadException
     */
    public static function getFileObjIndexed($fieldName, $idx, $maxSize=0,
                                             $acceptedMimeTypes=null)
    {
        $file = $_FILES[$fieldName];
        if (!isset($file['error'][$idx]) || is_array($file['error'][$idx]))
            throw new PqweUploadException('Invalid parameters.');
        switch ($file['error'][$idx]) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new PqweUploadException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new PqweUploadException('Exceeded filesize limit [machine configuration].');
            default:
                throw new PqweUploadException('Unknown errors.');
        }
        if ($maxSize>0 && $file['size'][$idx]>$maxSize)
            throw new PqweUploadException('Exceeded filesize limit [site coded].');
        if ($acceptedMimeTypes!==null) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (($ext = array_search(
                    $finfo->file($file['tmp_name'][$idx]),
                    $acceptedMimeTypes, true))===false)
            throw new PqweUploadException('Invalid file format.');
        }
        return $file;
    }
    /**
     * getUploadedFile
     *
     * like move_uploaded_file, but with extra checks.
     *
     * @static
     * @param string $fieldName The key in the $_FILES array
     * @param string $outFile The name of the final file
     * @param int $maxSize Maximum file size, 0 for no limit
     * @param array $acceptedMimeTypes Array of mime types to accept,
     * null for no checks
     * @return void
     * @throws PqweUploadException
     */
    public static function getUploadedFile($fieldName, $outFile, $maxSize=0,
                                           $acceptedMimeTypes=null)
    {
        $file = self::getFileObj($fieldName, $maxSize, $acceptedMimeTypes);
        if (file_exists($outFile))
            throw new PqweUploadException('Out file already exists');
        if (!move_uploaded_file($file['tmp_name'], $outFile))
            throw new PqweUploadException('Failed to move uploaded file.');
    }

    /**
     * getUploadedFiles
     *
     * like move_uploaded_file, but with extra checks.
     *
     * @static
     * @param string $fieldName The key in the $_FILES array
     * @param array $outFiles The names of the final files
     * @param int $maxSize Maximum file size, 0 for no limit
     * @param array $acceptedMimeTypes Array of mime types to accept,
     * null for no checks
     * @return void
     * @throws PqweUploadException
     */
    public static function getUploadedFiles($fieldName, $outFiles, $maxSize=0,
                                            $acceptedMimeTypes=null)
    {
        if (count($outFiles)<count($_FILES[$fieldName]['name']))
            throw new PqweUploadException('Too few names');
        for ($i=0; $i<count($_FILES[$fieldName]['name']); ++$i) {
            $file = self::getFileObjIndexed($fieldName, $i, $maxSize, $acceptedMimeTypes);
            if (file_exists($outFiles[$i]))
                throw new PqweUploadException('Out file already exists');
            if (!move_uploaded_file($file['tmp_name'][$i], $outFiles[$i]))
                throw new PqweUploadException('Failed to move uploaded file.');
        }
    }
}

