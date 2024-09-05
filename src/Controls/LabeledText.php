<?php

namespace PinaRoleBasedResourceAccess\Controls;

use Pina\Controls\Control;
use Pina\Html;

class LabeledText extends Control
{
    protected $text = '';

    public function load($text, $style = 'info')
    {
        $this->text = $text;
        if ($style) {
            $this->addClass('label-'.$style);
        }
    }

    protected function draw()
    {
        return Html::tag('span', $this->drawInnerBefore() . $this->drawInner() . $this->drawInnerAfter(), $this->makeAttributes(['class' => 'label']));
    }

}