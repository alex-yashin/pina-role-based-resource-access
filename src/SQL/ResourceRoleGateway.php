<?php

namespace PinaRoleBasedResourceAccess\SQL;

use Exception;
use Pina\Data\Schema;
use Pina\DB\ForeignKey;
use Pina\TableDataGateway;
use Pina\Types\IntegerType;
use function PinaRoleBasedResourceAccess\__;

class ResourceRoleGateway extends TableDataGateway
{
    protected static $table = 'resource_role';

    /**
     * @return Schema
     * @throws Exception
     */
    public function getSchema(): Schema
    {
        $schema = new Schema();
        $schema->add('resource_id', __('Ресурс'), IntegerType::class)->setMandatory();
        $schema->add('role_id', __('Группа'), IntegerType::class)->setMandatory();
        $schema->addCreatedAt(__('Дата создания'));
        $schema->setPrimaryKey(['resource_id', 'role_id']);
        return $schema;
    }

    public function getForeignKeys(): array
    {
        return [
            (new ForeignKey('resource_id'))->references(ResourceGateway::instance()->getTable(), 'id'),
            (new ForeignKey('role_id'))->references(RoleGateway::instance()->getTable(), 'id'),
        ];
    }

    public function withRoles($roleIds = []): ResourceRoleGateway
    {
        $this->calculate('GROUP_CONCAT(`role`.id SEPARATOR ";")', 'roles')
            ->leftJoin(
                RoleGateway::instance()
                    ->on('id', 'role_id')
            )
            ->groupBy($this->getAlias() . '.resource_id');
        return $this;
    }

    public function withResource(): ResourceRoleGateway
    {
        $this->leftJoin(
            ResourceGateway::instance()
            ->on('id', 'resource_id')
            ->select('*')
        );
        return $this;
    }

    /**
     * Добавляет фильтр по id роли, не трогая список присвоенных ролей
     * @param $roleId - id в таблице role
     * @return $this
     */
    public function whereHasRole($roleId): ResourceRoleGateway
    {
        $this
            ->innerJoin(
                ResourceRoleGateway::instance()
                    ->alias('has_role')
                    ->on('resource_id', 'resource_id')
                    ->onBy('role_id', $roleId)
            );

        return $this;
    }

    public function withRolesGroupCode(): ResourceRoleGateway
    {
        $this->calculate('GROUP_CONCAT(`role`.code SEPARATOR ";")', 'group_codes')
            ->leftJoin(
                RoleGateway::instance()
                    ->on('id', 'role_id')
            )
            ->groupBy($this->getAlias() . '.resource_id');
        return $this;
    }
}
