<?php
namespace pqwe\View;

interface IView {
    public function isEmpty();
    public function setViewFile($file);
    public function render();
}

