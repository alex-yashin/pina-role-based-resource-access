<?php


namespace PinaRoleBasedResourceAccess\Controls;


use Pina\Controls\FormCheckbox;
use Pina\Html;

class FormCheckListItem extends FormCheckbox
{
    protected function drawInput()
    {
        $options = ['type' => $this->type, 'value' => $this->checkedValue];
        $id = uniqid('ch');

        if ($this->name) {
            $options['name'] = $this->name;
            $options['id'] = $id;
        }

        $selected = is_array($this->value) ? in_array($this->checkedValue, $this->value) : !is_null($this->value) && $this->value == $this->checkedValue;
        if ($selected) {
            $options['checked'] = 'checked';
        }

        $r = Html::tag('input', '', $options);
        $r .= Html::tag('label', $this->compact ? '' : $this->title, ['for' => $id]);
        return $r;
    }
}