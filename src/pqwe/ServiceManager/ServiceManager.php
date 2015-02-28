<?php
namespace pqwe\ServiceManager;

class ServiceManager {
    protected $instances;

    protected $invokables;
    protected $factories;
    protected $shared;

    public function __construct($config) {
        $this->instances['config'] = $config;
        $this->invokables = isset($config['service_manager']['invokables']) ?
                        $config['service_manager']['invokables'] : array();
        $this->factories = isset($config['service_manager']['factories']) ?
                        $config['service_manager']['factories'] : array();
        $this->shared = isset($config['service_manager']['shared']) ?
                        $config['service_manager']['shared'] : array();
    }
    public function isRegistered($what) {
        return  isset($this->instances[$what]) ||
                isset($this->invokables[$what]) ||
                isset($this->factories[$what]);
    }
    public function get($what) {
        if (isset($this->instances[$what]))
            return $this->instances[$what];
        /* invokables */
        if (isset($this->invokables[$what])) {
            $className = "\\".$this->invokables[$what];
            $instance = new $className();
            $instance->serviceManager = $this;
            if (isset($this->shared[$what]) && $this->shared[$what]===false)
                return $instance;
            $this->instances[$what] = $instance;
            return $this->instances[$what];
        }
        /* factories */
        if (isset($this->factories[$what])) {
            $className = "\\".$this->factories[$what];
            $factory = new $className();
            $instance = $factory->create($this);
            if (isset($this->shared[$what]) && $this->shared[$what]===false)
                return $instance;
            $this->instances[$what] = $instance;
            return $this->instances[$what];
        }
        throw new \Exception("SM: class $what not found");
    }
    public function set($what, $obj) {
        $this->instances[$what] = $obj;
    }
}

