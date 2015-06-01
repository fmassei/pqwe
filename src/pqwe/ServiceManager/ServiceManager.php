<?php
/**
 * ServiceManager class
 */
namespace pqwe\ServiceManager;

use \pqwe\Exception\PqweServiceManagerException;

/**
 * The ServiceManager keeps the application configuration, returns objects
 * creating them on demand, and @todo.
 */
class ServiceManager {
    /** @var array $instances Cached object instances */
    protected $instances;

    /** @var array $invokables Cached invokable objects */
    protected $invokables;
    /** @var array $invokables Cached factory objects */
    protected $factories;
    /** @var array $invokables Cached shared objects */
    protected $shared;

    /**
     * constructor
     *
     * @param array $config The application configuration
     */
    public function __construct($config) {
        $this->instances['config'] = $config;
        $this->invokables = isset($config['service_manager']['invokables']) ?
                        $config['service_manager']['invokables'] : array();
        $this->factories = isset($config['service_manager']['factories']) ?
                        $config['service_manager']['factories'] : array();
        $this->shared = isset($config['service_manager']['shared']) ?
                        $config['service_manager']['shared'] : array();
    }

    /**
     * Check if something with the passed name is registered somewhere
     *
     * @param string $what The name to search
     * @return bool
     */
    public function isRegistered($what) {
        return  isset($this->instances[$what]) ||
                isset($this->invokables[$what]) ||
                isset($this->factories[$what]);
    }

    /**
     * Get the object specified by the passed name
     *
     * @param string $what Name of the object
     * @return mixed
     * @throws \pqwe\Exception\PqweServiceManagerException
     */
    public function get($what) {
        if ($what=="")
            throw new PqweServiceManagerException("invalid class requested");
        if (isset($this->instances[$what]))
            return $this->instances[$what];
        /* invokables */
        if (isset($this->invokables[$what])) {
            $className = $this->invokables[$what];
            if ($className[0]!="\\")
                $className = "\\".$className;
            $instance = new $className();
            $instance->serviceManager = $this;
            if (isset($this->shared[$what]) && $this->shared[$what]===false)
                return $instance;
            $this->instances[$what] = $instance;
            return $this->instances[$what];
        }
        /* factories */
        if (isset($this->factories[$what])) {
            $className = $this->factories[$what];
            if ($className[0]!="\\")
                $className = "\\".$className;
            $factory = new $className();
            $instance = $factory->create($this);
            if (isset($this->shared[$what]) && $this->shared[$what]===false)
                return $instance;
            $this->instances[$what] = $instance;
            return $this->instances[$what];
        }
        throw new PqweServiceManagerException("class '$what' not found");
    }

    /**
     * Manually register an object under a name
     *
     * @param string $what Name of the object
     * @param mixed $obj Object to register
     * @return void
     */
    public function set($what, $obj) {
        $this->instances[$what] = $obj;
    }

    /**
     * Like get(), but with an internal switch specialized in returning default
     * pqwe objects if no user alternative is specified.
     *
     * This should be used only interally by pqwe, to load default classes when
     * none is specified.
     *
     * @param string $what Name of the object
     * @return mixed
     * @throws \pqwe\Exception\PqweServiceManagerException
     */
    public function getOrGetDefault($what) {
        if ($this->isRegistered($what))
            return $this->get($what);
        switch($what) {
        case 'pqwe_routes':
            $routes = new \pqwe\Routing\RoutesDefault();
            $this->set($what, $routes);
            break;
        case 'pqwe_router':
            $config = $this->get('config');
            $routes = $this->getOrGetDefault('pqwe_routes');
            $router = new \pqwe\Routing\RouterDefault($this, $config['routes'],
                                                             $routes);
            $this->set($what, $router);
            break;
        default:
            throw new PqweServiceManagerException("internal calss '$what' not found");
        }
        return $this->get($what);
    }
}

