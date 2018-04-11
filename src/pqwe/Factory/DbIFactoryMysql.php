<?php
/**
 * DbIFactoryMysql class
 */
namespace pqwe\Factory;

use pqwe\Exception\PqweServiceManagerException;
use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapterMysql;

/**
 * Factory object for the DbAdapterMysql
 *
 * @see \pqwe\Db\DbAdapterMysql
 */
class DbIFactoryMysql implements IFactory {
    /**
     * creates a DbAdapterMysql object
     *
     * @param ServiceManager $sm A ServiceManager instance
     * @return DbAdapterMysql
     * @throws PqweServiceManagerException
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

