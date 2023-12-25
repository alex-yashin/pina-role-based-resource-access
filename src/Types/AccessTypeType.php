<?php


namespace PinaRoleBasedResourceAccess\Types;


use Pina\Types\EnumType;

use PinaRoleBasedResourceAccess\AccessTypeRegistry;

class AccessTypeType extends EnumType
{
    public function __construct()
    {
        $this->variants = AccessTypeRegistry::getVariants();
    }

    public function getSize(): int
    {
        return 16;
    }

    public function getSQLType(): string
    {
        return "varchar(".$this->getSize().")";
    }

}