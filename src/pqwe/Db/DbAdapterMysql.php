<?php
namespace pqwe\Db;

class DbAdapterMysql implements IDb {
    protected $mysqli;
    public function __construct($hostname, $username, $password, $database)
    {
        $this->mysqli = new \mysqli($hostname, $username, $password, $database);
        $this->mysqli->set_charset("utf8");
    }
    public function __destruct() {
        $this->mysqli->close();
    }
    public function prepare($str) {
        return $this->mysqli->prepare($str);
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
        //error_log('mysqli query: '.str_replace("\n", " ", $str));
        //error_log('bt'.$this->getBTStr(debug_backtrace()));
        if (($ret = $this->mysqli->query($str))===false)
            error_log("query failed: ".str_replace("\n"," ",$str)." [err: ".$this->mysqli->error."]");
        return $ret;
    }
    public function beginTransaction() {
        if ($this->mysqli->autocommit(false)===false)
            throw new \Exception($this->mysqli->error);
    }
    public function commit() {
        if ($this->mysqli->commit()===false)
            throw new \Exception($this->mysqli->error);
        $this->mysqli->autocommit(true);
    }
    public function rollback() {
        if ($this->mysqli->rollback()===false)
            throw new \Exception($this->mysqli->error);
        $this->mysqli->autocommit(true);
    }
    public function error() { return $this->mysqli->error; }
}

