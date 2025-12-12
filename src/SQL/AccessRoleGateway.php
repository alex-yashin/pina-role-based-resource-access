<?php

namespace PinaRoleBasedResourceAccess\SQL;

use Exception;
use Pina\Data\Schema;
use Pina\DB\ForeignKey;
use Pina\TableDataGateway;
use PinaRoleBasedResourceAccess\Types\AccessType;

use PinaRoleBasedResourceAccess\Types\RoleType;

use function Pina\__;

class AccessRoleGateway extends TableDataGateway
{
    public function getTable(): string
    {
        return 'access_role';
    }

    /**
     * @return Schema
     * @throws Exception
     */
    public function getSchema(): Schema
    {
        $schema = parent::getSchema();
        $schema->add('access_id', __('Доступ'), AccessType::class)->setMandatory();
        $schema->add('role_id', __('Группа'), RoleType::class)->setMandatory();
        $schema->addCreatedAt(__('Дата создания'));
        $schema->setPrimaryKey(['access_id', 'role_id']);
        return $schema;
    }

    public function getForeignKeys(): array
    {
        return [
            (new ForeignKey('access_id'))->references(AccessGateway::instance()->getTable(), 'id'),
            (new ForeignKey('role_id'))->references(RoleGateway::instance()->getTable(), 'id'),
        ];
    }

}
