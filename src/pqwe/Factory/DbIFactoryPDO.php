<?php
/**
 * DbIFactoryPDO class
 */
namespace pqwe\Factory;

use pqwe\Exception\PqweServiceManagerException;
use pqwe\ServiceManager\ServiceManager;
use pqwe\Db\DbAdapterPDO;

/**
 * Factory object for the DbAdapterPDO
 *
 * @see \pqwe\Db\DbAdapterPDO
 */
class DbIFactoryPDO implements IFactory {
    /**
     * creates a DbAdapterPDO object
     *
     * @param ServiceManager $sm A ServiceManager instance
     * @return DbAdapterPDO
     * @throws PqweServiceManagerException
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

