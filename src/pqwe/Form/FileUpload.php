<?php
namespace pqwe\Form;

class FileUpload {
    public function getUploadedFile($fieldName)
    {
        try {
            if (    !isset($_FILES[$fieldName]['error']) ||
                    is_array($_FILES[$fieldName]['error']))
                throw new \RuntimeException('Invalid parameters.');
            switch ($_FILES[$fieldName]['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit [machine configuration].');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }
            if ($_FILES[$fieldName]['size'] > 100000000)
                throw new \RuntimeException('Exceeded filesize limit [site coded].');
            /*$finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($_FILES[$fieldName]['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),true))
                throw new \RuntimeException('Invalid file format.');*/
            $baseName = sha1_file($_FILES[$fieldName]['tmp_name']);
            $path = $this->serviceManager->get('config');
            $path = $path['upload_dir'];
            while (file_exists($path.'/'.$baseName.'.'.$ext.'_Q.'.$ext)) {
                $baseName .= '_1';
            }
            $fname = sprintf('%s.%s', $baseName, $ext);
            if (!move_uploaded_file(
                    $_FILES[$fieldName]['tmp_name'],
                    $path.'/'.$fname))
                throw new \RuntimeException('Failed to move uploaded file.');
            return $fname;
        } catch (\RuntimeException $e) {
            throw $e;
        }
    }
}
