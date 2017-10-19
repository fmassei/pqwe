<?php
/**
 * Service class
 */
namespace pqwe\ServiceManager;

/**
 * The Service class can be used as a base class for classes created by the
 * ServiceManager
 */
class Service {
    /** @var ServiceManager $serviceManager the serviceManager */
    public $serviceManager;

    /**
     * @var ServiceManager $serviceManager the serviceManager
     */
    public function __construct(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Called by the serviceManager upon creation
     */
    public function init() {
    }
}

