<?php

namespace PinaRoleBasedResourceAccess\Types;

use Pina\Types\EnumType;

use function Pina\__;

class ConnectionType extends EnumType
{
    public function __construct()
    {
        $this->variants = [
            ['id' => 'static', 'title' => __('Статическая')],
            ['id' => 'dynamic', 'title' => __('Динамическая')],
        ];
    }
}