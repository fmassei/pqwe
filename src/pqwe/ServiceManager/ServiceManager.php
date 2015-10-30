<?php
/**
 * ServiceManager class
 */
namespace pqwe\ServiceManager;

use \pqwe\Exception\PqweServiceManagerException;

/**
 * The ServiceManager keeps the application configuration, returns objects
 * creating them on demand, and much more.
 *
 * The ServiceManager is one of the main objects of pqwe, typically created once
 * at the beginning of the application and passed to (or automatically injected
 * in) all the objects around the application. Using it is extremely simple.
 *
 * ### Construct the ServiceManager
 * To create a ServiceManager instance you need to pass the "configuration"
 *
 *      $serviceManager = new ServiceManager($config);
 *
 * where the configuration is an array. You can get all the configuration
 * back asking for the key "config".
 *
 *      $config = $serviceManager->get("config");
 *
 * ### Set the ServiceManager up
 * The ServiceManager will use the "service_manager" entry in the configuration
 * to know how to work. This entry looks like:
 *
 *      'service_manager' => array(
 *          'invokables' => array(
 *              NAME => CLASSNAME,
 *              NAME => CLASSNAME,
 *              ...
 *          ),
 *          'factories' => array(
 *              NAME => CLASSNAME,
 *              ...
 *          ),
 *          'shared' => array(
 *              NAME => true|false,
 *              ...
 *          )
 *      )
 *
 * when the get($NAME) method is called, the ServiceManager will look in this
 * array to find the association between the passed name and the class.
 *
 * ### Objects creation
 * The ServiceManager will keep instances of the requested objects for future
 * requests, hence creating an object with a given name just once. You can
 * see it as an object cache, if you want to.
 *
 * Depending on where the association is, the ServiceManager will act
 * differently:
 * - invokables: the associated class will be created calling a constructor
 *   with no parameters, the ServiceManager instance injected in the new object,
 *   and the object cached for later requests.
 * - factories: the associated class will be created calling a constructor
 *   with no parameters, the method create($serviceManager) of the new object
 *   called, and its return value (which will be the constructed object)
 *   cached.
 *   Even if the ServiceManager will not check, is a good idea for the factory
 *   class to implement {@see \pqwe\Factory\FactoryInterface}.
 * - shared: any name present in the "shared" array with a "false" value will
 *   skip the caching process, giving you always new objects. By default all
 *   the objects are shared.
 *
 * ### Manually setting objects
 * If you want to, you can also set an object instance manually inside the
 * ServiceManager, with the set() method.
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
        case 'pqwe_acl':
            $acl = new \pqwe\ACL\ACL($this);
            $this->set($what, $acl);
            break;
        default:
            throw new PqweServiceManagerException("internal class '$what' not found");
        }
        return $this->get($what);
    }
}

