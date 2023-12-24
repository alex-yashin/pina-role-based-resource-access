<?php


namespace PinaRoleBasedResourceAccess\Collections;


use Pina\Data\DataCollection;
use Pina\Data\Schema;
use PinaRoleBasedResourceAccess\SQL\RoleGateway;

class RoleCollection extends DataCollection
{
    public function makeQuery()
    {
        return RoleGateway::instance();
    }

    public function getListSchema(): Schema
    {
        return parent::getListSchema()->forgetField('lisp_condition');
    }

}