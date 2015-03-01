<?php
namespace pqwe\View;

class View implements IView {
    protected $viewFile = null;
    protected $raw_content = null;

    public function __construct($file=null) {
        $this->setViewFile($file);
    }
    public function isEmpty() {
        return $this->viewFile===null && $this->raw_content===null;
    }
    public function setViewFile($file) {
        $this->viewFile = $file;
    }
    public function setContent($str) {
        $this->raw_content = $str;
    }
    public function return_output() {
        if ($this->raw_content!==null) {
            return $this->raw_content;
        } else if ($this->viewFile!==null) {
            ob_start();
            include($this->viewFile);
            return ob_get_clean();
        } else {
            throw new \Exception('View: empty');
        }
    }
    public function render() {
        echo $this->return_output();
    }
}

