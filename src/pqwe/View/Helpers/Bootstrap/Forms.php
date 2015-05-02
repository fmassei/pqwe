<?php
namespace pqwe\View\Helpers\Bootstrap;

class Forms {
    public function renderText($label, $name, $value, $hasError, $placeholder=null) {
        $class = "form-group".($hasError?" has-error has-feedback":"");
        $errorId = $name.'Error';
        $describedBy = $hasError?' aria-describedby='.$errorId:'';
        $ph = ($placeholder!==null)?' placeholder="'.$placeholder.'"':'';
        $ret = <<<EOL
            <div class="{$class}">
            <label class="control-label" for="{$name}">{$label}</label>
            <input class="form-control" type="text" value="{$value}" name="{$name}" id="{$name}"{$describedBy}{$ph}>
EOL;
        if ($hasError)
            $ret .= <<<EOL
                <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="{$errorId}" class="sr-only">(error)</span>
EOL;
        $ret .= '</div>';
        return $ret;
    }
    public function renderTextarea($label, $name, $value, $hasError, $rows=10) {
        $class = "form-group".($hasError?" has-error has-feedback":"");
        $errorId = $name.'Error';
        $describedBy = $hasError?' aria-describedby='.$errorId:'';
        $ret = <<<EOL
            <div class="{$class}">
            <label class="control-label" for="{$name}">{$label}</label>
            <textarea class="form-control" name="{$name}" id="{$name}" rows="{$rows}"{$describedBy}>{$value}</textarea>
EOL;
        if ($hasError)
            $ret .= <<<EOL
                <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="{$errorId}" class="sr-only">(error)</span>
EOL;
        $ret .= '</div>';
        return $ret;
    }
    public function renderSelect($options, $selectedId, $name, $zeroCat=false, $withButton=false, $extraAttrs='') {
        $ret = '';
        if ($withButton)
            $ret .= '<div class="input-group">';
        $ret .= '<select class="form-control" name="'.$name.'" id="'.$name.'" '.$extraAttrs.'>';
        if ($zeroCat)
            $ret .= '<option value="0"></option>';
        foreach ($options as $option) {
            $selected = $option->id==$selectedId ? ' selected="selected"':'';
            $ret .= '<option value="'.$option->id.'"'.$selected.'>'.$option->name.'</option>';
        }
        $ret .= '</select>';
        if ($withButton)
            $ret .= '<span class="input-group-btn"><button class="btn btn-default" type="button" id="'.$name.'btn"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></span>'.
                '</div>';
        return $ret;
    }
}

