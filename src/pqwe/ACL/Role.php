<?php
/**
 * ACL Role class
 */
namespace pqwe\ACL;

/**
 * An ACL role
 */
class Role {
    /** @var string $name The name of the role */
    protected $name;
    /** @var array $parents Role parents */
    protected $parents;

    /**
     * constructor
     *
     * @param string $name The name of the role
     * @param array $parents Role parents
     */
    public function __construct($name, $parents) {
        $this->name = $name;
        $this->parents = $parents;
    }

    /**
     * check if this role is allowed to access a resource
     *
     * @param \pqwe\ACL\Resource $resource The resource to access
     * @param string $privilege An optional resource privilege
     * @return bool
     */
    public function isResourceAllowed($resource, $privilege=null) {
        if (($res = $resource->isRoleAllowed($this->name, $privilege))!==null)
            return $res;
        foreach ($this->parents as $parent)
            if (($res = $resource->isRoleAllowed($parent->name, $privilege))!==null)
                return $res;
        return false;
    }
}

