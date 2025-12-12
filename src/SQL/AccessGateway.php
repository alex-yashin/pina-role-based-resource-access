<?php

namespace PinaRoleBasedResourceAccess\SQL;

use Exception;
use Pina\Data\Schema;
use Pina\TableDataGateway;
use Pina\Types\StringType;
use PinaRoleBasedResourceAccess\Types\AccessRoleRelation;
use PinaRoleBasedResourceAccess\Types\AccessTypeType;
use function Pina\__;

class AccessGateway extends TableDataGateway
{
    public function getTable(): string
    {
        return 'access';
    }

    /**
     * @throws Exception
     */
    public function getSchema(): Schema
    {
        $schema = parent::getSchema();
        $schema->addAutoincrementPrimaryKey();
        $schema->add('type', __('Тип доступа'), AccessTypeType::class)->setDefault('resource')->setMandatory();
        $schema->add('resource', __('Ресурс'), StringType::class)->setMandatory();
        $schema->addUniqueKey(['type', 'resource']);
        $schema->add('title', __('Название'), StringType::class)->setMandatory();
        $schema->add('role_ids', __('Роли'), new AccessRoleRelation());
        return $schema;
    }

    public function resourceAccesses(): AccessGateway
    {
        $this->select('resource');

        $this->innerJoin(
            AccessRoleGateway::instance()
                ->on('access_id', 'id')
                ->innerJoin(
                    RoleGateway::instance()
                        ->alias('role_code')
                        ->on('id', 'role_id')
                        ->calculate('GROUP_CONCAT(role_code.code SEPARATOR ";")', 'roles')
                )
        );

        $this->groupBy($this->getAlias() . '.id');
        return $this;
    }

    public function whereHasRole($code)
    {
        return $this->innerJoin(
            AccessRoleGateway::instance()->on('access_id', 'id')
                ->innerJoin(
                    RoleGateway::instance()->on('id', 'role_id')
                        ->onBy('code', $code)
                )
        );
    }
}
