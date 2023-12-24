<?php

namespace PinaRoleBasedResourceAccess\Types;

use Pina\Html;
use Pina\Types\EnumType;

use function Pina\__;

class StyleType extends EnumType
{
    public function __construct()
    {
        $this->variants = [
            'info' => ['id' => 'info', 'title' => __('Информация')],
            'warning' => ['id' => 'warning', 'title' => __('Предупреждение')],
            'danger' => ['id' => 'danger', 'title' => __('Внимание')],
            'light-info' => ['id' => 'light-info', 'title' => __('Сообщение')],
        ];
    }

    public function draw($value): string
    {
        $text = $this->format($value);

        return Html::tag('span', $text, ['class' => 'label label-' . $value]);
    }

}