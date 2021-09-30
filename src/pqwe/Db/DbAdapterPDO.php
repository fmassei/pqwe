<?php
/**
 * PDO adapter
 */
namespace pqwe\Db;

use \pqwe\Exception\PqweDbException;

/**
 * Adapter for the PDO object
 */
class DbAdapterPDO implements IDb {
    /**
     * Underlay PDO object
     */
    public $pdo;

    /**
     * constructor
     *
     * creates a PDO object, connecting to the db.
     *
     * @param string $dsn DSN
     * @param string $username Username
     * @param string $password Password
     * @param string $options Options to pass to the PDO constructor
     */
    public function __construct($dsn, $username, $password, $options)
    {
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }

    /**
     * prepare a statement
     *
     * @param string $str Statement
     * @return \PDOStatement|false A prepared statement object
     */
    public function prepare($str) {
        return $this->pdo->prepare($str);
    }

    /**
     * get a description of the last PDO error
     */
    private function getErrorString() {
        $info = $this->pdo->errorInfo();
        return $info[2];
    }

    /**
     * execute a query
     *
     * @param string $str Query
     * @return \PDOStatement|false A database-specific result set
     */
    public function query($str) {
        return $ret = $this->pdo->query($str);
    }

    /**
     * Begin a transaction
     *
     * @return void
     * @throws \pqwe\Exception\PqweDbException
     */
    public function beginTransaction() {
        if ($this->pdo->beginTransaction()===false)
            throw new PqweDbException($this->getErrorString());
    }

    /**
     * Commit a transaction
     *
     * @return void
     * @throws \pqwe\Exception\PqweDbException
     */
    public function commit() {
        if ($this->pdo->commit()===false)
            throw new PqweDbException($this->getErrorString());
    }

    /** 
     * Rollback a transaction
     *
     * @return void
     * @throws \pqwe\Exception\PqweDbException
     */
    public function rollback() {
        if ($this->pdo->rollback()===false)
            throw new PqweDbException($this->getErrorString());
    }

    /**
     * Get the last error
     *
     * @return string The last occurred error
     */
    public function error() {
        return $this->getErrorString();
    }
}

