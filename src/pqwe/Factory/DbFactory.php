<?php
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapter;

class DbFactory implements FactoryInterface {
    public function create(ServiceManager $sm) {
        $config = $sm->get('config');
        return new DbAdapter($config['db']['hostname'],
                             $config['db']['username'],
                             $config['db']['password'],
                             $config['db']['database']);
    }
}

