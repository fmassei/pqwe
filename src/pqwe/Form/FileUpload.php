<?php
namespace pqwe\Form;

use \pqwe\Exception\PqweUploadException;

class FileUpload {
    public static function getUploadedFile($fieldName, $outFile, $maxSize=0,
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
        if (file_exists($outFile))
            throw new PqweUploadException('Out file already exists');
        if (!move_uploaded_file($file['tmp_name'], $outFile))
            throw new PqweUploadException('Failed to move uploaded file.');
    }
}

