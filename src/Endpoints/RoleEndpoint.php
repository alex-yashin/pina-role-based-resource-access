<?php

namespace PinaRoleBasedResourceAccess\Endpoints;

use Exception;
use Pina\Http\DelegatedCollectionEndpoint;
use Pina\Http\Request;
use PinaRoleBasedResourceAccess\Collections\RoleCollection;
use Pina\App;
use function Pina\__;

class RoleEndpoint extends DelegatedCollectionEndpoint
{

    /**
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->composer->configure(__("Роли"), __('Добавить роль'));
        $this->collection = App::make(RoleCollection::class);
    }

}
