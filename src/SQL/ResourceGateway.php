<?php

namespace PinaRoleBasedResourceAccess\SQL;

use Exception;
use Pina\Data\Schema;
use Pina\SQL;
use Pina\TableDataGateway;
use Pina\Types\StringType;
use PinaRoleBasedResourceAccess\Types\CheckedRelation;
use function PinaRoleBasedResourceAccess\__;

class ResourceGateway extends TableDataGateway
{
    protected static $table = 'resource';

    /**
     * @throws Exception
     */
    public function getSchema(): Schema
    {
        $schema = new Schema();
        $schema->addAutoincrementPrimaryKey('id', 'ID');
        $schema->add('title', __('Название'), StringType::class)->setMandatory();
        $schema->add('resource', __('Ресурс'), StringType::class)->setMandatory();
        $schema->addUniqueKey('resource');
        $schema->add('role_ids', __('Роли'), new CheckedRelation(new ResourceRoleGateway(), 'resource_id', 'role_id', new RoleGateway()));
        return $schema;
    }

    public function whereRoles($roles): ResourceGateway
    {
        $this->innerJoin(
            ResourceRoleGateway::instance()
            ->on('resource_id', 'id')
            ->onBy('role_id', $roles)
        );
        return $this;
    }

    public function withRoles(): ResourceGateway
    {
        $this->leftJoin(
            SQL::subquery(
                ResourceRoleGateway::instance()
                    ->select('resource_id')
                    ->calculate('GROUP_CONCAT(role_id SEPARATOR ";")', 'roles')
                    ->orderBy('resource_id')
                    ->groupBy('resource_id')
            )
                ->alias('resource_role')
                ->select('roles')
                ->on('resource_id', 'id')
        );
        return $this;
    }

    public function whereHasRole($roleId)
    {
        $this->innerJoin(
            ResourceRoleGateway::instance()
                ->alias('has_role')
                ->on('resource_id', 'id')
                ->onBy('role_id', $roleId)
        );
    }

    public function withRoleTitles(): ResourceGateway
    {
        $this->calculate(
            '(' . ResourceRoleGateway::instance()
            ->calculate('GROUP_CONCAT(title SEPARATOR "<br/>")')
            ->leftJoin(
                RoleGateway::instance()
                ->on('id', 'role_id')
            )
            ->where('resource_role.resource_id = resource.id')
            ->groupBy('resource_role.resource_id') . ')', 'roleTitles');
        
        return $this;
    }

    public function resourceAccesses($roles = array()): ResourceGateway
    {
        $this->select('resource')
            ->calculate('GROUP_CONCAT(code SEPARATOR ";")', 'roles');
        if (!empty($roles)) {
            $this->leftJoin(
                ResourceRoleGateway::instance()
                ->on('resource_id', 'id')
                ->onBy('role_id', $roles)
                ->leftJoin(
                    RoleGateway::instance()
                    ->on('id', 'role_id')
                )
            );
        } else {
            $this->leftJoin(
                ResourceRoleGateway::instance()
                ->on('resource_id', 'id')
                ->leftJoin(
                    RoleGateway::instance()
                    ->on('id', 'role_id')
                )
            );
        }
        $this->groupBy(ResourceGateway::instance()->getAlias() . '.id');
        return $this;
    }
}
