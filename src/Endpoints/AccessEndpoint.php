<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Pina\App;
use Pina\Http\DelegatedCollectionEndpoint;
use Pina\Http\Request;
use PinaRoleBasedResourceAccess\Collections\AccessCollection;
use function Pina\__;

class AccessEndpoint extends DelegatedCollectionEndpoint
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->composer->configure(__("Права доступа"), __('Создать доступ'));
        $this->collection = App::make(AccessCollection::class);
    }

}
