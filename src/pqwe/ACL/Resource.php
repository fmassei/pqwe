<?php
namespace pqwe\ACL;

class Resource {
    protected $name;
    protected $parent;
    protected $allowedRoles = array();
    public function __construct($name, $parent) {
        $this->name = $name;
        $this->parent = $parent;
    }
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

