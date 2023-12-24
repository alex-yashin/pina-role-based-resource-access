<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Pina\App;
use Pina\Http\DelegatedCollectionEndpoint;
use Pina\Http\Request;
use PinaRoleBasedResourceAccess\Collections\ResourceCollection;
use function Pina\__;

class ResourceRoleEndpoint extends DelegatedCollectionEndpoint
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->composer->configure(__("Права доступа (настройки)"), __('Создать доступ'));
        $this->collection = App::make(ResourceCollection::class);
    }

}
