<?php
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;

interface FactoryInterface {
    public function create(ServiceManager $serviceManager);
}

