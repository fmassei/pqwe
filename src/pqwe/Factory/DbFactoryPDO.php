<?php
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapterPDO;

class DbFactoryPDO implements FactoryInterface {
    public function create(ServiceManager $sm) {
        $config = $sm->get('config');
        return new DbAdapterPDO($config['db']['dsn'],
                                $config['db']['username'],
                                $config['db']['password'],
                                $config['db']['options']);
    }
}

