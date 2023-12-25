<?php


namespace PinaRoleBasedResourceAccess\Collections;


use Pina\Data\DataCollection;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;

class AccessCollection extends DataCollection
{
    public function makeQuery()
    {
        return AccessGateway::instance();
    }
}