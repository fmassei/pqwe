<?php
/**
 * Mysqli adapter
 */
namespace pqwe\Db;

use \pqwe\Exception\PqweDbException;

/**
 * Adapter for the mysqli object
 */
class DbAdapterMysql implements IDb {
    /**
     * Underlay mysqli object
     */
    public $mysqli;

    /**
     * constructor
     *
     * creates a mysqli object, connecting to the db. It also sets the charset
     * to "utf8": remember to change it if you really want the connection in
     * a different one.
     *
     * @param string $hostname Hostname
     * @param string $username Username
     * @param string $password Password
     * @param string $database Database name
     */
    public function __construct($hostname, $username, $password, $database)
    {
        $this->mysqli = new \mysqli($hostname, $username, $password, $database);
        $this->mysqli->set_charset("utf8");
    }

    /**
     * destructor
     *
     * destroys the mysqli object
     */
    public function __destruct() {
        $this->mysqli->close();
    }

    /**
     * prepare a statement
     *
     * @param string $str Statement
     * @return \mysqli_stmt A prepared statement object
     */
    public function prepare($str) {
        return $this->mysqli->prepare($str);
    }

    /**
     * execute a query
     *
     * @param string $str Query
     * @return mixed A database-specific result set
     */
    public function query($str) {
        return $this->mysqli->query($str);
    }

    /**
     * Begin a transaction
     *
     * @return void
     * @throws \pqwe\Exception\PqweDbException
     */
    public function beginTransaction() {
        if ($this->mysqli->autocommit(false)===false)
            throw new PqweDbException($this->mysqli->error);
    }

    /**
     * Commit a transaction
     *
     * @return void
     * @throws \pqwe\Exception\PqweDbException
     */
    public function commit() {
        if ($this->mysqli->commit()===false)
            throw new PqweDbException($this->mysqli->error);
        $this->mysqli->autocommit(true);
    }

    /** 
     * Rollback a transaction
     *
     * @return void
     * @throws \pqwe\Exception\PqweDbException
     */
    public function rollback() {
        if ($this->mysqli->rollback()===false)
            throw new PqweDbException($this->mysqli->error);
        $this->mysqli->autocommit(true);
    }

    /**
     * Get the last error
     *
     * @return string The last occurred error
     */
    public function error() { return $this->mysqli->error; }
}

