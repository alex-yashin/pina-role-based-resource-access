<?php


namespace PinaRoleBasedResourceAccess\Types;


use Pina\App;
use Pina\Controls\FormSelect;
use Pina\Controls\FormStatic;
use Pina\Html;
use PinaRoleBasedResourceAccess\Controls\FormCheckList;
use Pina\Types\Relation;

class CheckedRelation extends Relation
{
    /**
     * @return FormSelect
     */
    protected function makeSelect()
    {
        return App::make(FormCheckList::class);
    }

    /**
     * @param mixed $value
     * @return string
     * @throws \Exception
     */
    public function draw($value): string
    {
        $items = $this->makeDirectoryQuery()->whereId($value)->selectTitle()->column('title');
        $r = '';
        foreach ($items as $item) {
            $r .= Html::nest('div', $this->drawCheck(true) . ' ' . $item);
        }

        return $r;
    }

    protected function drawCheck($checked)
    {
        if ($checked) {
            return Html::nest('i.mdi mdi-check-circle text-success');
        }
        return Html::nest('i.mdi mdi-close-circle text-warning');
    }

}