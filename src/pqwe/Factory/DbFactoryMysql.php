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
        $key = $this->getConfigKey();
        return new DbAdapterMysql($config[$key]['hostname'],
                                  $config[$key]['username'],
                                  $config[$key]['password'],
                                  $config[$key]['database']);
    }

    /**
     * Get the config key used to store the connection data
     *
     * @return string
     */
    protected function getConfigKey() {
        return 'db';
    }
}

