<?php

namespace PinaRoleBasedResourceAccess\Types;

use Pina\App;
use Pina\TableDataGateway;
use Pina\Types\QueryDirectoryType;
use PinaRoleBasedResourceAccess\Controls\LabeledText;
use PinaRoleBasedResourceAccess\SQL\RoleGateway;

class RoleType extends QueryDirectoryType
{
    /**
     * @return TableDataGateway
     * @throws \Exception
     */
    protected function makeQuery(): TableDataGateway
    {
        return RoleGateway::instance()->orderBy('title', 'asc');
    }

    public function draw($value): string
    {
        $list = $this->makeQuery()->whereId($value)->selectTitle()->select('style')->get();
        $r = [];
        foreach ($list as $item) {
            $r [] = $this->makeLabel($item['title'], $item['style']);
        }
        return implode('', $r);
    }

    protected function makeLabel($text, $style = 'info')
    {
        /** @var LabeledText $label */
        $label = App::make(LabeledText::class);
        $label->load($text, $style);
        return $label;
    }

}