<?php
/**
 * IDb interface
 */
namespace pqwe\Db;

/**
 * interface for database adapters
 *
 * Each class implementing this interface will also have a public property
 * reflecting the underlaying db object. The whole point of having an
 * interface is to create and pass those objects in an easier way through
 * the ServiceManager.
 * The objects implementing this interface will also probably throw a
 * PqweDbException in case of errors.
 */
interface IDb {
    /**
     * prepare a statement
     *
     * @param string $str Statement
     * @return mixed A database-specific prepared statement object
     */
    public function prepare($str);
    /**
     * execute a query
     *
     * @param string $str Query
     * @return mixed A database-specific result set
     */
    public function query($str);
    /**
     * Begin a transaction
     *
     * @return void
     */
    public function beginTransaction();
    /**
     * Commit a transaction
     *
     * @return void
     */
    public function commit();
    /** 
     * Rollback a transaction
     *
     * @return void
     */
    public function rollback();
    /**
     * Get the last error
     *
     * @return mixed A database-specific error
     */
    public function error();
}

