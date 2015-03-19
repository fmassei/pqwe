<?php
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

class ControllerHTML extends ControllerBase {
    protected $layoutView;
    protected $viewFolderName;
    protected $viewFolderPath;
    protected $layoutFile;

    private $actualDir;

    public function __construct($serviceManager) {
        parent::__construct($serviceManager);
        $this->viewFolderName = 'view';
        $this->layoutFile = 'layout.phtml';
    }
    public function preAction($routeMatch) {
        $this->layoutView = new View();
        $this->actualDir =
            \pqwe\Utils\Namespaces::getFirst($routeMatch['controller']);
        $this->viewFolderPath = \pqwe\Utils\Files::makePath($this->actualDir,
                                                $this->viewFolderName);
        $fpath = \pqwe\Utils\Files::makePath($this->viewFolderPath,
                                             $this->layoutFile);
        $this->layoutView->setViewFile($fpath);
    }
    public function postAction(IView $view, $action) {
        if ($view->isEmpty()) {
            $fpath = \pqwe\Utils\Files::makePath($this->viewFolderPath,
                                                 $action.'.phtml');
            $view->setViewFile($fpath);
        }
        $this->layoutView->content = $view->return_output();
        $this->layoutView->render();
    }
}

