<?php
namespace pqwe\View\Helpers\Vanilla;

class Forms {
    protected $tableMode = false;
    public function setTableMode($hasTableMode=true) {
        $this->tableMode = $hasTableMode;
    }
    public function renderText($label, $name, $value, $hasError, $placeholder=null) {
        $class = $hasError?'error':'';
        $ph = ($placeholder!==null)?' placeholder="'.$placeholder.'"':'';
        if ($this->tableMode) {
            $bb = '<tr><td>'; $bs = '</td><td>'; $be = '</td></tr>';
        } else {
            $bb = $bs = $be = '';
        }
        $ret = <<<EOL
            {$bb}<label for="{$name}">{$label}</label>{$bs}
            <input class="{$class}" type="text" value="{$value}" name="{$name}" id="{$name}"{$ph}>{$be}
EOL;
        return $ret;
    }
    public function renderTextarea($label, $name, $value, $hasError, $rows=10) {
        $class = $hasError?'error':'';
        if ($this->tableMode) {
            $bb = '<tr><td>'; $bs = '</td><td>'; $be = '</td></tr>';
        } else {
            $bb = $bs = $be = '';
        }
        $ret = <<<EOL
            {$bb}<label for="{$name}">{$label}</label>{$bs}
            <textarea class="{$class}" name="{$name}" id="{$name}" rows="{$rows}">{$value}</textarea>{$be}
EOL;
        return $ret;
    }
    public function renderSelect($label, $name, $options, $selectedId, $zeroCat=false, $withButton=false, $extraAttrs='') {
        if ($this->tableMode) {
            $bb = '<tr><td>'; $bs = '</td><td>'; $be = '</td></tr>';
        } else {
            $bb = $bs = $be = '';
        }
        $ret = $bb.'<label for="'.$name.'">'.$label.'</label>'.$bs;
        $ret .= '<select name="'.$name.'" id="'.$name.'" '.$extraAttrs.'>';
        if ($zeroCat)
            $ret .= '<option value="0"></option>';
        foreach ($options as $option) {
            $selected = $option->id==$selectedId ? ' selected="selected"':'';
            $ret .= '<option value="'.$option->id.'"'.$selected.'>'.$option->name.'</option>';
        }
        $ret .= '</select>'.$be;
        return $ret;
    }
}

