<?php

namespace PinaRoleBasedResourceAccess\Types;

use Exception;
use Pina\TableDataGateway;
use Pina\Types\QueryDirectoryType;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;

class ResourcesType extends QueryDirectoryType
{
    /**
     * @return TableDataGateway
     * @throws Exception
     */
    protected function makeQuery(): TableDataGateway
    {
        return AccessGateway::instance()
            ->selectId()
            ->calculate("CONCAT(title, ' (', url, ')')", 'title')
            ->orderBy('resource', 'asc');
    }
}
