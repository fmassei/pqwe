<?php
/**
 * Factory interface
 */
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;

/**
 * Classes implementing this interface can be used as factory objects by the
 * ServiceManager.
 */
interface IFactory {
    /**
     * creates the object
     *
     * @param \pqwe\ServiceManager\ServiceManager $serviceManager The ServiceManager
     * @return mixed The created object
     */
    public function create(ServiceManager $serviceManager);
}

