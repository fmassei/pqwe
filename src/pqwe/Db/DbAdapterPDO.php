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
     * @return void
     */
    public function __construct($dsn, $username, $password, $options)
    {
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }

    /**
     * prepare a statement
     *
     * @param string $str Statement
     * @return \PDOStatement A prepared statement object
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

    /*private function getBTStr($arr)
    {
        $ret = '';
        foreach($arr as $a) {
            $f = explode('/', $a['file']);
            $ret .= $f[count($f)-2]."/".$f[count($f)-1].":".$a['line'].'('.$a['function'].')';
        }
        return $ret;
    }*/
    /**
     * execute a query
     *
     * @param string $str Query
     * @return \PDOStatement A database-specific result set
     * @todo find another way to activate query debugging
     */
    public function query($str) {
        /*if (($ret = $this->pdo->query($str))===false) {
            error_log("query failed: ".str_replace("\n"," ",$str)." [err: ".$this->getErrorString()."]");
        }
        return $ret;*/
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

