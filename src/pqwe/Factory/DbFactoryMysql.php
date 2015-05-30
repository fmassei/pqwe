<?php
/**
 * DbFactoryMysql class
 */
namespace pqwe\Factory;

use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapterMysql;

/**
 * Factory object for the DbAdapterMysql
 *
 * @see \pqwe\Db\DbAdapterMysql
 */
class DbFactoryMysql implements FactoryInterface {
    /**
     * creates a DbAdapterMysql object
     *
     * @param \pqwe\ServiceManager\ServiceManager $sm A ServiceManager instance
     * @return \pqwe\Db\DbAdapterMysql
     */
    public function create(ServiceManager $sm) {
        $config = $sm->get('config');
        return new DbAdapterMysql($config['db']['hostname'],
                                  $config['db']['username'],
                                  $config['db']['password'],
                                  $config['db']['database']);
    }
}

