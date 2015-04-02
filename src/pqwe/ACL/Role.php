<?php
namespace pqwe\ACL;

class Role {
    protected $name;
    protected $parents;
    public function __construct($name, $parents) {
        $this->name = $name;
        $this->parents = $parents;
    }
    public function isResourceAllowed($resource, $privilege=null) {
        if (($res = $resource->isRoleAllowed($this->name, $privilege))!==null)
            return $res;
        foreach ($this->parents as $parent)
            if (($res = $resource->isRoleAllowed($parent->name, $privilege))!==null)
                return $res;
        return false;
    }
}

