<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Pina\Data\DataCollection;
use Pina\Data\QueryDataCollection;
use Pina\Http\DelegatedCollectionEndpoint;
use PinaRoleBasedResourceAccess\SQL\RoleGateway;
use function Pina\__;

class RoleEndpoint extends DelegatedCollectionEndpoint
{
    protected function getCollectionTitle(): string
    {
        return __("Роли");
    }

    protected function makeDataCollection(): DataCollection
    {
        return new QueryDataCollection(RoleGateway::instance());
    }
}
