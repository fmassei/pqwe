<?php
/**
 * ACL class
 */
namespace pqwe\ACL;

use pqwe\ServiceManager\ServiceManager;
use pqwe\Exception\PqweServiceManagerException;

/**
 * ACL (Access Control List) class
 */
class ACL {
    /** @var ServiceManager $serviceManager A
    * ServiceManager instance */         
    protected $serviceManager;
    /** @var array $roles List of roles */
    protected $roles;
    /** @var array $resources List of resources */
    protected $resources;

    /**
     * constructor
     *
     * @param ServiceManager $serviceManager A
     *  ServiceManager instance
     * @throws PqweServiceManagerException
     */
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
        $this->roles = array();
        $this->resources = array();
        $config = $this->serviceManager->get('config');
        if (!isset($config['acl']))
            return;
        if (isset($config['acl']['roles']))
            foreach($config['acl']['roles'] as $name=>$parents)
                $this->addRole($name, $parents);
        if (isset($config['acl']['resources']))
            foreach($config['acl']['resources'] as $name=>$parent)
                $this->addResource($name, $parent);
        if (isset($config['acl']['allow']))
            foreach ($config['acl']['allow'] as $name=>$rp)
                foreach ($rp as $resource=>$privileges)
                    $this->allow($name, $resource, $privileges);
        if (isset($config['acl']['deny']))
            foreach ($config['acl']['deny'] as $name=>$rp)
                foreach ($rp as $resource=>$privileges)
                    $this->deny($name, $resource, $privileges);
    }

    /**
     * From a name, return a Role, or null
     *
     * @param string $roleName Name of the Role
     * @return \pqwe\ACL\Role|null
     */
    protected function name2role($roleName) {
        if (!isset($this->roles[$roleName]))
            return null;
        return $this->roles[$roleName];
    }

    /**
     * From a name, return a Resource, or null
     *
     * @param string $resourceName Name of the Role
        * @return \pqwe\ACL\Resource|null
     */
    protected function name2resource($resourceName) {
        if (!isset($this->resources[$resourceName]))
            return null;
        return $this->resources[$resourceName];
    }

    /**
     * Add a role to the role list
     *
     * @param string $roleName Name of the role
     * @param string|array $parentNames Name(s) of the parent roles, if any
     * @return void
     */
    public function addRole($roleName, $parentNames=null) {
        $parents = array();
        if ($parentNames!==null) {
            if (is_string($parentNames))
                $parentNames = (array)$parentNames;
            foreach($parentNames as $parentName) {
                $parent = $this->name2role($parentName);
                if ($parent!==null)
                    $parents[] = $parent;
            }
        }
        $role = new Role($roleName, $parents);
        $this->roles[$roleName] = $role;
    }

    /**
     * Add a resource to the resource list
     * @param string $resourceName Name of the resource
     * @param string $parentName Name of resource parent, if any
     * @return void
     */
    public function addResource($resourceName, $parentName=null) {
        $parent = ($parentName!==null) ? $this->name2resource($parentName)
                                       : null;
        $resource = new Resource($resourceName, $parent);
        $this->resources[$resourceName] = $resource;
    }

    /**
     * Allow resource access to a role
     *
     * @param string $roleName Name of the role
     * @param string $resourceName Name of the resource
     * @param string|array $privileges Name or list of privileges
     * @return void
     */
    public function allow($roleName, $resourceName, $privileges=null) {
        $this->resources[$resourceName]->addRole($roleName, true, $privileges);
    }

    /**
     * Deny resource access to a role
     *
     * @param string $roleName Name of the role
     * @param string $resourceName Name of the resource
     * @param string|array $privileges Name or list of privileges
     * @return void
     */
    public function deny($roleName, $resourceName, $privileges=null) {
        $this->resources[$resourceName]->addRole($roleName, false, $privileges);
    }

    /**
     * Get the default role name
     *
     * The default role is just the first role added.
     *
     * @return string
     */
    public function getDefaultRoleName() {
        if (count($this->roles)<=0)
            return '';
        return reset($this->roles)->name;
    }

    /**
     * Check if a role is allowed to access a resource with a certain privilege
     *
     * @param string $roleName Name of the role
     * @param string $resourceName Name of the resource
     * @param string|array $privileges Name or list of privileges
     * @return bool
     */
    protected function isAllowed_role($roleName, $resourceName, $privileges=null) {
        if (    !isset($this->resources[$resourceName]) ||
                !isset($this->roles[$roleName]))
            return false;
        $resource = $this->resources[$resourceName];
        $role = $this->roles[$roleName];
        return $role->isResourceAllowed($resource, $privileges);
    }

    /**
     * Check if a role or an array of roles is allowed to access a resource
     * with a certain privilege
     *
     * @param string|array $roleNames Name of the role(s)
     * @param string $resourceName Name of the resource
     * @param string|array $privileges Name or list of privileges
     * @return bool
     */
    public function isAllowed($roleNames, $resourceName, $privileges=null) {
        if (is_string($roleNames)) {
            return $this->isAllowed_role($roleNames, $resourceName, $privileges);
        } else if (is_array($roleNames)) {
            foreach ($roleNames as $roleName)
                if ($this->isAllowed_role($roleName, $resourceName, $privileges))
                    return true;
            return false;
        } else {
            return false;
        }
    }
}

