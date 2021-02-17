<?php
/**
 * ControllerHTML class
 */
namespace pqwe\Controller;

use pqwe\Exception\PqweMVCException;
use pqwe\View\View;
use pqwe\View\IView;
use pqwe\Routing\RouteMatch;

/**
 * Controller specialized for HTML actions
 */
class ControllerHTML extends ControllerBase {
    /** @var \pqwe\View\View $layoutView View for the layout */
    protected $layoutView;
    /** @var string $viewFolderName Name of the folder containing the views */
    protected $viewFolderName;
    /** @var string $viewFolerPath Path of the folder containing the views */
    protected $viewFolderPath;
    /** @var string $layoutFile Name of the layout view file */
    protected $layoutFile;
    /** @var bool $noLayout If set, the layout rendering is skipped */
    protected $noLayout = false;
    
    /** @var string $actualDir Path of the controller file, used internally */
    private $actualDir;

    /**
     * constructor
     *
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager A
     * ServiceManager instance
     */
    public function __construct($serviceManager) {
        parent::__construct($serviceManager);
        $this->viewFolderName = 'view';
        $this->layoutFile = 'layout.phtml';
    }

    /**
     * Set the name of the file that will be used as the layout.
     *
     * Note: this function has to be called before the preAction() takes
     * place.
     *
     * @param string $layoutFile Filename of the layout (no path)
     * @return void
     */
    public function setLayoutFile($layoutFile) {
        $this->layoutFile = $layoutFile;
    }

    /**
     * callback, called by the MVC object before the action method
     *
     * This overridden method sets up the layout view, making it ready to be
     * rendered (or modified by derived classes in the (pre)action methods).
     *
     * @param RouteMatch $routeMatch The matched route.
     * @return void
     */
    public function preAction(&$routeMatch) {
        $this->layoutView = new View();
        $this->actualDir =
            \pqwe\Utils\Namespaces::getFirst($routeMatch->controller);
        $this->viewFolderPath = \pqwe\Utils\Files::makePath($this->actualDir,
                                                $this->viewFolderName);
        $fpath = \pqwe\Utils\Files::makePath($this->viewFolderPath,
                                             $this->layoutFile);
        $this->layoutView->setViewFile($fpath);
    }

    /**
     * callback, called by the MVC object after the action method
     *
     * This overridden method renders the passed $view and the $layoutView.
     *
     * + If $view is an empty IView, the default one will be used (a file
     * called $action.".phtml" in the $viewFolderPath).
     * + If the $noLayout flag is set, the layout will not be rendered.
     *
     * @param IView $view The View object returned by the action method.
     * @param string $action Name of the called action
     * @return void
     * @throws PqweMVCException
     */
    public function postAction(IView $view, $action) {
        if ($view->isEmpty()) {
            $fPath = \pqwe\Utils\Files::makePath($this->viewFolderPath,
                                                 $action.'.phtml');
            $view->setViewFile($fPath);
        }
        $view->assign("controller", $this);
        if ($this->noLayout) {
            $view->render();
        } else {
            $this->layoutView->assign("action", $action);
            $this->layoutView->assign("content", $view->return_output());
            $this->layoutView->render();
        }
    }

    /**
     * set the noLayout flag, disabling the layout rendering
     *
     * @param bool $noLayout (de)activate the layout rendering
     * @return void
     */
    public function setNoLayout($noLayout=true) {
        $this->noLayout = $noLayout;
    }
}

