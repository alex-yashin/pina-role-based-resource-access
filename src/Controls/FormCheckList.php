<?php


namespace PinaRoleBasedResourceAccess\Controls;


use Pina\App;
use Pina\Controls\FormSelect;
use Pina\Html;

class FormCheckList extends FormSelect
{
    protected function drawInput()
    {
        return Html::tag('div', $this->drawOptions());
    }

    protected function drawOptions()
    {
        $options = '';

        foreach ($this->variants as $variant) {
            $title = isset($variant['title']) ? $variant['title'] : '';
            $id = isset($variant['id']) ? $variant['id'] : $title;

            if (empty($id)) {
                continue;
            }

            /** @var FormCheckListItem $checkbox */
            $checkbox = App::make(FormCheckListItem::class);
            $checkbox->setTitle($title);
            $checkbox->setName($this->name.'[]');//всегда multiple
            $checkbox->setValue($this->value);
            $checkbox->setOptionValue($id);

            $options .= $checkbox;
        }

        return $options;
    }
}