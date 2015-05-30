<?php
/**
 * ACL Resource class
 */
namespace pqwe\ACL;

/**
 * An ACL resource
 */
class Resource {
    /** @var string $name The name of the resource */
    protected $name;
    /** @var Resource $parent Parent of this resource */
    protected $parent;
    /** @var array The Roles allowed to access this resource */
    protected $allowedRoles = array();

    /**
     * constructor
     *
     * @param string $name The name of the resource
     * @param Resource $parent Parent of this resource
     */
    public function __construct($name, $parent) {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * grant access to a role, with an optional privileges
     *
     * @param string $roleName Name of the Role
     * @param bool $allow Allow or deny
     * @param string|array $privileges Name or list of privileges
     * @return void
     */
    public function addRole($roleName, $allow, $privileges=null) {
        if (!isset($this->allowedRoles[$roleName]))
            $this->allowedRoles[$roleName] = array('priv'=>array());
        if ($privileges===null) {
            $this->allowedRoles[$roleName]['allow'] = $allow;
        } else {
            if (is_string($privileges))
                $privileges = (array)$privileges;
            foreach($privileges as $privilege)
                $this->allowedRoles[$roleName]['priv'][$privilege] = $allow;
        }
    }
    /**
     * check if a role is allowed to access this resource
     *
     * @param string $roleName Name of the Role
     * @param string $priviledge An optional resource privilege
     * @return bool
     */
    public function isRoleAllowed($roleName, $privilege=null) {
        if (isset($this->allowedRoles[$roleName])) {
            if (    $privilege!==null &&
                    isset($this->allowedRoles[$roleName]['priv']) &&
                    isset($this->allowedRoles[$roleName]['priv'][$privilege]))
                return $this->allowedRoles[$roleName]['priv'][$privilege];
            if (isset($this->allowedRoles[$roleName]['allow']))
                return $this->allowedRoles[$roleName]['allow'];
        }
        if ($this->parent!==null)
            return $this->parent->isRoleAllowed($roleName);
        return null;
    }
}

