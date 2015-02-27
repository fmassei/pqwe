<?php
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapter;

class DbFactoryMysql implements FactoryInterface {
    public function create(ServiceManager $sm) {
        $config = $sm->get('config');
        return new DbAdapterMysql($config['db']['hostname'],
                                  $config['db']['username'],
                                  $config['db']['password'],
                                  $config['db']['database']);
    }
}

