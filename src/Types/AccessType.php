<?php

namespace PinaRoleBasedResourceAccess\Types;

use Pina\TableDataGateway;
use Pina\Types\QueryDirectoryType;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;

class AccessType extends QueryDirectoryType
{
    /**
     * @return TableDataGateway
     * @throws \Exception
     */
    protected function makeQuery(): TableDataGateway
    {
        return AccessGateway::instance()
            ->selectId()
            ->calculate("CONCAT(title, ' (', resource, ')')", 'title')
            ->orderBy('resource', 'asc');
    }

}