<?php
namespace pqwe\View;

class View implements ViewInterface {
    protected $viewFile = null;

    public function __construct($file=null) {
        $this->setViewFile($file);
    }

    public function setViewFile($file) {
        $this->viewFile = $file;
    }
    public function return_output() {
        ob_start();
        include($this->viewFile);
        return ob_get_clean();
    }
    public function render() {
        echo $this->return_output();
    }
}

