<?php
namespace pqwe\Db;

use \pqwe\Exception\PqweDbException;

class DbAdapterPDO implements IDb {
    public $pdo;

    public function __construct($dsn, $username, $password, $options)
    {
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }
    public function prepare($str) {
        return $this->pdo->prepare($str);
    }
    private function getErrorString() {
        $info = $this->pdo->errorInfo();
        return $info[2];
    }
    private function getBTStr($arr)
    {
        $ret = '';
        foreach($arr as $a) {
            $f = explode('/', $a['file']);
            $ret .= $f[count($f)-2]."/".$f[count($f)-1].":".$a['line'].'('.$a['function'].')';
        }
        return $ret;
    }
    public function query($str) {
        if (($ret = $this->pdo->query($str))===false) {
            error_log("query failed: ".str_replace("\n"," ",$str)." [err: ".$this->getErrorString()."]");
        }
        return $ret;
    }
    public function beginTransaction() {
        if ($this->pdo->beginTransaction()===false)
            throw new PqweDbException($this->getErrorString());
    }
    public function commit() {
        if ($this->pdo->commit()===false)
            throw new PqweDbException($this->getErrorString());
    }
    public function rollback() {
        if ($this->pdo->rollback()===false)
            throw new PqweDbException($this->getErrorString());
    }
    public function error() {
        return $this->getErrorString();
    }
}

