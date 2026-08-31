<?php

namespace tonimareta\moodle\criterias;

use tonimareta\moodle\Criteria;
use tonimareta\moodle\interfaces\OptionInterface;
use tonimareta\moodle\options\CategoryIncludeOption;

/**
 * @property int $id - the category id
 * @property string $ids - category ids separated by commas
 * @property string $name - the category name
 * @property int $parent - the parent category id
 * @property string $idnumber - category idnumber - user must have 'moodle/category:manage' to search on idnumber
 * @property int $visible - whether the returned categories must be visible or hidden
 * @property CategoryIncludeOption $categoryIncludeOption
 */
class CategoryCriteria extends Criteria
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'ids',
            'name',
            'parent',
            'idnumber',
            'visible',
            'categoryIncludeOption',
        ];
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $items = parent::getItems();

        if (!empty($items['categoryIncludeOption'])) {
            unset($items['categoryIncludeOption']);
        }

        return $items;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        if (!$this->categoryIncludeOption) {
            return [];
        }

        return $this->categoryIncludeOption->getItems();
    }
}