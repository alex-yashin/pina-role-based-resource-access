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
use function PinaRoleBasedResourceAccess\__;

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

        $schema->add('resource_ids', __('Ресурсы'), new CheckedRelation(new ResourceRoleGateway(), 'role_id', 'resource_id', new ResourceGateway()));

        return $schema;
    }

    public function getTriggers(): array
    {
        return [
            [
                $this->getTable(),
                'before insert',
                "SET NEW.`order` = (SELECT IFNULL(MAX(`order`),0)+1 FROM role);"
            ],
        ];
    }

    public function forAccess(): RoleGateway // для совместимости с полем group таблицы content - там enum
    {
        $this->whereBy('code', [
            'registered',
            'root',
            'support'
        ]);
        return $this;
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

    public function getMaxPosition(): ?string
    {
        return $this->calculate('MAX(`order`)', 'max_order')->value('max_order');
    }

    public function getSimpleList($except = null): array
    {
        $list = $this->select('id')
            ->select('title')
            ->whereStatic();
        if (!empty($except)) {
            $list->whereNotBy('id', $except);
        }
        $list = $list->get();
        return array_merge([['id' => 0, 'title' => __('Нет')]], $list);
    }
    
    public function setGroupCodeList(): array
    {
        return $this->select('code')
            ->select('code')
            ->get();
    }

    public function withElementsCount(): RoleGateway
    {
//         $this->calculate('SUM(IF(role_element.type = "currency",1,0))', 'currencies_count')
//             ->calculate('SUM(IF(role_element.type = "resource",1,0))', 'resources_count')
//             ->calculate('SUM(IF(role_element.type = "report",1,0))', 'reports_count')
//             ->leftJoin(RoleElementGateway::instance()
//                 ->on('role_id', 'id')
//                 ->alias('role_element')
//             )->groupBy('id');
        
        return $this;
    }

    public function withResources(): RoleGateway
    {
        $this->calculate('GROUP_CONCAT(role_resource.resource_id SEPARATOR ";")', 'resources')
            ->leftJoin(ResourceRoleGateway::instance()
                ->on('role_id', 'id')
                ->alias('role_resource')
            )->groupBy('role_resource.role_id');
        
        return $this;
    }

}
