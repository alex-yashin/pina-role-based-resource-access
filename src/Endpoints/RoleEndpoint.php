<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Exception;
use Pina\Data\DataCollection;
use Pina\Http\DelegatedCollectionEndpoint;
use Pina\Http\Request;
use PinaRoleBasedResourceAccess\Collections\RoleCollection;
use Pina\App;
use function Pina\__;

class RoleEndpoint extends DelegatedCollectionEndpoint
{
    protected function getCollectionTitle(): string
    {
        return __("Роли");
    }

    protected function makeDataCollection(): DataCollection
    {
        return App::make(RoleCollection::class);
    }
}
