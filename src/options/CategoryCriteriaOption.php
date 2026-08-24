<?php

namespace tonimareta\moodle\options;

use tonimareta\moodle\Option;

/**
 * @property int $id - the category id
 * @property string $ids - category ids separated by commas
 * @property string $name - the category name
 * @property int $parent - the parent category id
 * @property string $idnumber - category idnumber - user must have 'moodle/category:manage' to search on idnumber
 * @property int $visible - whether the returned categories must be visible or hidden
 */
class CategoryCriteriaOption extends Option
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
        ];
    }

    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return ['key', 'value'];
    }
}