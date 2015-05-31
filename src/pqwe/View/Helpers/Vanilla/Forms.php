<?php
/**
 * Forms class
 */
namespace pqwe\View\Helpers\Vanilla;

/**
 * class for rendering form fields
 */
class Forms {
    /** @var bool $tableMode if set, use tables to format */
    protected $tableMode = false;

    /**
     * set the $tableMode property
     *
     * @param bool $hasTableMode (de)activate the table mode
     * @return void
     */
    public function setTableMode($hasTableMode=true) {
        $this->tableMode = $hasTableMode;
    }

    /**
     * Return the rendered text field
     *
     * @param string $label Field label
     * @param string $name "name" attribute, also used for the "id" attribute
     * @param string $value Initial field value
     * @param bool $hasError If the field has to be marked an containing an
     * error
     * @param string $placeHolder The "placeholder" attribute
     * @return string
     */
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

    /**
     * Return the rendered textarea field
     *
     * @param string $label Field label
     * @param string $name "name" attribute, also used for the "id" attribute
     * @param string $value Initial field value
     * @param bool $hasError If the field has to be marked an containing an
     * error
     * @param int $rows "rows" attribute
     * @return string
     */
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

    /**
     * Return the rendered select field
     *
     * @param string $label Field label
     * @param string $name "name" attribute, also used for the "id" attribute
     * @param array $options An array of objects containing the "id" and "name"
     * properties
     * @param int $selectedId The id to be selected by default
     * @param bool $zeroCat If true, add an empty option on top the select
     * @param bool $withButton If true, adds an extra button after the select
     * @param string $extraAttrs Extra attributes to add to the select
     * @return string
     */
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

