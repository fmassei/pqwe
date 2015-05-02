<?php
namespace pqwe\View\Helpers\Vanilla;

class Forms {
    public function renderText($label, $name, $value, $hasError, $placeholder=null) {
        $class = $hasError?'error':'';
        $ph = ($placeholder!==null)?' placeholder="'.$placeholder.'"':'';
        $ret = <<<EOL
            <label for="{$name}">{$label}</label>
            <input class="{$class}" type="text" value="{$value}" name="{$name}" id="{$name}"{$ph}>
EOL;
        return $ret;
    }
    public function renderTextarea($label, $name, $value, $hasError, $rows=10) {
        $class = $hasError?'error':'';
        $ret = <<<EOL
            <label for="{$name}">{$label}</label>
            <textarea class="{$class}" name="{$name}" id="{$name}" rows="{$rows}">{$value}</textarea>
EOL;
        return $ret;
    }
    public function renderSelect($options, $selectedId, $name, $zeroCat=false, $withButton=false, $extraAttrs='') {
        $ret = '';
        $ret .= '<select name="'.$name.'" id="'.$name.'" '.$extraAttrs.'>';
        if ($zeroCat)
            $ret .= '<option value="0"></option>';
        foreach ($options as $option) {
            $selected = $option->id==$selectedId ? ' selected="selected"':'';
            $ret .= '<option value="'.$option->id.'"'.$selected.'>'.$option->name.'</option>';
        }
        $ret .= '</select>';
        return $ret;
    }
}

