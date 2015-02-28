<?php
namespace pqwe\View;

interface IView {
    public function setViewFile($file);
    public function render();
}

