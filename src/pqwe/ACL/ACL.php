<?php
namespace pqwe\ACL;

class ACL {
    protected $roles;
    protected $resources;

    public function __construct() {
        $this->roles = array();
        $this->resources = array();
    }
    protected function name2role($roleName) {
        if (!isset($this->roles[$roleName]))
            return null;
        return $this->roles[$roleName];
    }
    protected function name2resource($resourceName) {
        if (!isset($this->resources[$resourceName]))
            return null;
        return $this->resources[$resourceName];
    }
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
    public function addResource($resourceName, $parentName=null) {
        $parent = ($parentName!==null) ? $this->name2resource($parentName)
                                       : null;
        $resource = new Resource($resourceName, $parent);
        $this->resources[$resourceName] = $resource;
    }
    public function allow($roleName, $resourceName, $privileges=null) {
        $this->resources[$resourceName]->addRole($roleName, true, $privileges);
    }
    public function deny($roleName, $resourceName, $privileges=null) {
        $this->resources[$resourceName]->addRole($roleName, false, $privileges);
    }
    public function isAllowed($roleName, $resourceName, $privilege=null) {
        if (    !isset($this->resources[$resourceName]) ||
                !isset($this->roles[$roleName]))
            return false;
        $resource = $this->resources[$resourceName];
        $role = $this->roles[$roleName];
        return $role->isResourceAllowed($resource, $privilege);
    }
}

