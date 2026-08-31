<?php

namespace tonimareta\moodle\rules;

use tonimareta\moodle\Rule;

/**
 * @property int $id
 * @property string $name - category name
 * @property string $idnumber - category id number
 * @property int $parent - parent category id
 * @property string $description - category description
 * @property int $descriptionformat  - description format (1 = HTML, 0 = MOODLE, 2 = PLAIN, or 4 = MARKDOWN)
 */
class CategoryRule extends Rule
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'idnumber',
            'parent',
            'description',
            'descriptionformat',
        ];
    }
}