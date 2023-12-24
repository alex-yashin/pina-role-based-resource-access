<?php


namespace PinaRoleBasedResourceAccess\Collections;


use Pina\Data\DataCollection;
use PinaRoleBasedResourceAccess\SQL\ResourceGateway;

class ResourceCollection extends DataCollection
{
    public function makeQuery()
    {
        return ResourceGateway::instance();
    }
}