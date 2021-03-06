<?php
/**
 * IView interface
 */
namespace pqwe\View;

use pqwe\Exception\PqweMVCException;

/**
 * interface to be implemented by Views
 */
interface IView {
    /**
     * Check if the view is empty
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Associate the view with a file
     *
     * @param string $file Name of the view file
     * @return void
     */
    public function setViewFile($file);

    /**
     * Assign a named variable to the view.
     *
     * @param string $name Name of the variable
     * @param mixed $val Value of the variable
     * @return void
     */
    public function assign($name, $val);

    /**
     * echo the view contents
     *
     * @return void
     * @throws PqweMVCException
     */
    public function render();

    /**
     * Return the view output
     *
     * @return string
     * @throws PqweMVCException
     */
    public function return_output();
}

