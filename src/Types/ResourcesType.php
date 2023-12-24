<?php

namespace PinaRoleBasedResourceAccess\Types;

use Pina\TableDataGateway;
use Pina\Types\QueryDirectoryType;
use PinaRoleBasedResourceAccess\SQL\ResourceGateway;

class ResourcesType extends QueryDirectoryType
{
    /**
     * @return TableDataGateway
     * @throws \Exception
     */
    protected function makeQuery(): TableDataGateway
    {
        return ResourceGateway::instance()
            ->selectId()
            ->calculate("CONCAT(title, ' (', url, ')')", 'title')
            ->orderBy('resource', 'asc');
    }
}
