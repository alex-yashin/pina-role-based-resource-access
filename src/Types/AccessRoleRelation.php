<?php

namespace PinaRoleBasedResourceAccess\Types;

use Pina\Access;
use Pina\App;
use Pina\Html;
use PinaRoleBasedResourceAccess\SQL\AccessRoleGateway;
use PinaRoleBasedResourceAccess\SQL\RoleGateway;

class AccessRoleRelation extends CheckedRelation
{

    protected $resource = '';

    public function __construct()
    {
        parent::__construct(new AccessRoleGateway(), 'access_id', 'role_id', new RoleGateway());
    }

    public function setContext($context)
    {
        $this->resource = $context['resource'] ?? '';
        return $this;
    }

    public function format($value): string
    {
        if (empty($value)) {
            return '';
        }
        $codes = $this->makeDirectoryQuery()->whereId($value)->select('code')->column('code');

        return implode(', ', $this->expandWithForcedGroups($codes));
    }

    public function draw($value): string
    {
        if (empty($value)) {
            return '';
        }

        $codes = $this->makeDirectoryQuery()->whereId($value)->select('code')->column('code');
        $roles = $this->expandWithForcedGroups($codes);

        $r = '';
        foreach ($roles as $item) {
            $r .= Html::nest('div', $this->drawCheck(true) . ' ' . $item);
        }

        return $r;
    }

    protected function expandWithForcedGroups($codes)
    {
        $codes = array_unique(array_merge($codes, App::access()->getPermittedGroups($this->resource)));

        return RoleGateway::instance()->whereBy('code', $codes)->column('title');
    }

}