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
        $key = $this->getConfigKey();
        return new DbAdapterPDO($config[$key]['dsn'],
                                $config[$key]['username'],
                                $config[$key]['password'],
                                $config[$key]['options']);
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

