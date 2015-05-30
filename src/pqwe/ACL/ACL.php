<?php
/**
 * ACL class
 */
namespace pqwe\ACL;

/**
 * ACL (Access Control List) class
 */
class ACL {
    /** @var array $roles List of roles */
    protected $roles;
    /** @var array $resources List of resources */
    protected $resources;

    /**
     * constructor
     */
    public function __construct() {
        $this->roles = array();
        $this->resources = array();
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
     * Check if a role is allowed to access a resource with a certain privilege
     *
     * @param string $roleName Name of the role
     * @param string $resourceName Name of the resource
     * @param string|array $privileges Name or list of privileges
     * @return bool
     */
    public function isAllowed($roleName, $resourceName, $privilege=null) {
        if (    !isset($this->resources[$resourceName]) ||
                !isset($this->roles[$roleName]))
            return false;
        $resource = $this->resources[$resourceName];
        $role = $this->roles[$roleName];
        return $role->isResourceAllowed($resource, $privilege);
    }
}

