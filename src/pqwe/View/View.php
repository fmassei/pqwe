<?php
/**
 * View class
 */
namespace pqwe\View;

use \pqwe\Exception\PqweMVCException;

/**
 * Base class for views
 */
class View implements IView {
    /** @var string $viewFile Filename of the view */
    protected $viewFile = null;
    /** @var string $raw_content Raw content of the view */
    protected $raw_content = null;

    /**
     * constructor
     *
     * @param string $file Filename of the view
     */
    public function __construct($file=null) {
        $this->setViewFile($file);
    }

    /**
     * Check if the view is empty
     *
     * Return true if both $viewFile and $raw_content are null
     *
     * @return bool
     */
    public function isEmpty() {
        return $this->viewFile===null && $this->raw_content===null;
    }

    /**
     * Associate the view with a file
     *
     * @param string $file Name of the view file
     * @return void
     */
    public function setViewFile($file) {
        $this->viewFile = $file;
    }

    /**
     * Associate the view with the passed string
     *
     * @param string $str The raw_content to set
     * @return void
     */
    public function setContent($str) {
        $this->raw_content = $str;
    }

    /**
     * Return the view output
     *
     * + if $raw_content is set, return it
     * + otherwise, if $viewFile is set, return its contents (in OB)
     * + otherwise, throw an exception
     *
     * @return string
     * @throws \pqwe\Exception\PqweMVCException
     */
    public function return_output() {
        if ($this->raw_content!==null) {
            return $this->raw_content;
        } else if ($this->viewFile!==null) {
            ob_start();
            include($this->viewFile);
            return ob_get_clean();
        } else {
            throw new PqweMVCException('empty view');
        }
    }

    /**
     * echo the view contents
     *
     * @return void
     */
    public function render() {
        echo $this->return_output();
    }
}

