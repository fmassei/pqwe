<?php
/**
 * DbFactoryPDO class
 */
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapterPDO;

/**
 * Factory object for the DbAdapterPDO
 *
 * @see \pqwe\Db\DbAdapterPDO
 */
class DbFactoryPDO implements FactoryInterface {
    /**
     * creates a DbAdapterPDO object
     *
     * @param \pqwe\ServiceManager\ServiceManager $sm A ServiceManager instance
     * @return \pqwe\Db\DbAdapterPDO
     */
    public function create(ServiceManager $sm) {
        $config = $sm->get('config');
        return new DbAdapterPDO($config['db']['dsn'],
                                $config['db']['username'],
                                $config['db']['password'],
                                $config['db']['options']);
    }
}

