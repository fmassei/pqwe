<?php
/**
 * ControllerXML class
 */
namespace pqwe\Controller;

use pqwe\View\View;
use pqwe\View\IView;

/**
 * Controller specialized for XML actions
 */
class ControllerXML extends ControllerBase {
    /** @var \pqwe\View\View $layoutView View for the layout */
    protected $layoutView;
    /** @var string $viewFolderName Name of the folder containing the views */
    protected $viewFolderName;
    /** @var string $viewFolerPath Path of the folder containing the views */
    protected $viewFolderPath;

    /** @var string $version XML file version */
    protected $version;
    /** @var string $encoding XML file encoding */
    protected $encoding;

    /** @var string $actualDir Path of the controller file, used internally */
    private $actualDir;

    /**
     * constructor
     *
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager A
     * ServiceManager instance
     * @param string $version XML file version
     * @param string $encoding XML file encoding
     */
    public function __construct($serviceManager,
                                $version="1.0", $encoding="UTF-8") {
        parent::__construct($serviceManager);
        $this->viewFolderName = 'view';
        $this->version = $version;
        $this->encoding = $encoding;
    }

    /**
     * callback, called by the MVC object before the action method
     *
     * This overridden method sets up the layout view as an empty View
     *
     * @param \pqwe\Routing\RouteMatch $routeMatch The matched route.
     * @return void
     */
    public function preAction(&$routeMatch) {
        $this->layoutView = new View();
    }

    /**
     * callback, called by the MVC object after the action method
     *
     * This overridden method renders the passed $view and the $layoutView.
     *
     * + If $view is an empty IView, the default one will be used (a file
     * called $action.".phtml" in the $viewFolderPath).
     * + If the $layoutView is empty (as by default), if will be filled with
     * the appropriate XML header.
     *
     * @param \pqwe\View\IView $view The View object returned by the action
     * method.
     * @param string $action Name of the called action
     * @return void
     */
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

