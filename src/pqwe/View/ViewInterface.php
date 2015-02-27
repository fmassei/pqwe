<?php
namespace pqwe\View;

interface ViewInterface {
    public function setViewFile($file);
    public function render();
}

