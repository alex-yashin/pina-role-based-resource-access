<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Pina\Data\DataCollection;
use Pina\Data\QueryDataCollection;
use Pina\Http\DelegatedCollectionEndpoint;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;
use function Pina\__;

class AccessEndpoint extends DelegatedCollectionEndpoint
{
    protected function getCollectionTitle(): string
    {
        return __("Права доступа");
    }

    protected function makeDataCollection(): DataCollection
    {
        return new QueryDataCollection(AccessGateway::instance());
    }
}
