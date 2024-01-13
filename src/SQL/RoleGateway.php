<?php

namespace PinaRoleBasedResourceAccess\SQL;

use Pina\Data\Schema;
use Pina\TableDataGateway;
use Pina\Types\EnabledType;
use Pina\Types\StringType;
use PinaRoleBasedResourceAccess\Types\CheckedRelation;
use PinaRoleBasedResourceAccess\Types\ConnectionType;
use PinaRoleBasedResourceAccess\Types\LispType;
use PinaRoleBasedResourceAccess\Types\StyleType;
use function Pina\__;

class RoleGateway extends TableDataGateway
{
    protected static $table = 'role';

    /**
     * @return Schema
     * @throws \Exception
     */
    public function getSchema(): Schema
    {
        $schema = new Schema();
        $schema->addAutoincrementPrimaryKey('id', 'ID');

        $schema->add('code', __('Код'), StringType::class)->setMandatory()->setDefault(''); // для совместимости с кодом
        $schema->addUniqueKey('code');

        $schema->add('connection', __('Вид привязки'), ConnectionType::class);
        $schema->add('title', __('Название'), StringType::class)->setMandatory();
        $schema->add('style', __('Стиль'), StyleType::class);
        $schema->add('lisp_condition', __('LISP формула'), LispType::class);
        $schema->add('enabled', __('Статус'), EnabledType::class);

        $schema->add('access_ids', __('Ресурсы'), new CheckedRelation(new AccessRoleGateway(), 'role_id', 'access_id', new AccessGateway()));

        return $schema;
    }

    public function whereEnabled(): RoleGateway
    {
        $this->whereBy('enabled', 'Y');
        return $this;
    }

    public function whereStatic(): RoleGateway
    {
        $this->whereBy('connection', 'static');
        return $this;
    }

}
