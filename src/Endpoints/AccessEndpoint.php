<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Pina\App;
use Pina\Data\DataCollection;
use Pina\Http\DelegatedCollectionEndpoint;
use Pina\Http\Request;
use PinaRoleBasedResourceAccess\Collections\AccessCollection;
use function Pina\__;

class AccessEndpoint extends DelegatedCollectionEndpoint
{
    protected function getCollectionTitle(): string
    {
        return __("Права доступа");
    }

    protected function makeDataCollection(): DataCollection
    {
        return App::make(AccessCollection::class);
    }
}
