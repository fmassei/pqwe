<?php
namespace pqwe\Db;

interface IDb {
    public function prepare($str);
    public function query($str);
    public function beginTransaction();
    public function commit();
    public function rollback();
    public function error();
}

