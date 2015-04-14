<?php
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

class ControllerXML extends ControllerBase {
    protected $layoutView;
    protected $viewFolderName;
    protected $viewFolderPath;

    protected $version;
    protected $encoding;

    private $actualDir;

    public function __construct($serviceManager,
                                $version="1.0", $encoding="UTF-8") {
        parent::__construct($serviceManager);
        $this->viewFolderName = 'view';
        $this->version = $version;
        $this->encoding = $encoding;
    }
    public function preAction(&$routeMatch) {
        $this->layoutView = new View();
    }
    public function postAction(IView $view, $action) {
        if ($view->isEmpty()) {
            $fpath = \pqwe\Utils\Files::makePath($this->viewFolderPath,
                                                 $action.'.phtml');
            $view->setViewFile($fpath);
        }
        $this->layoutView->action = $action;
        $this->layoutView->setContent('<?xml version="'.$this->version.'" encoding="'.$this->encoding.'"?>'.$view->return_output());
        $this->layoutView->render();
    }
}

