<?php

namespace tonimareta\moodle\rules;

use tonimareta\moodle\Rule;

/**
 * @property int $id - category id to delete
 * @property int $newparent - the parent category to move the contents to, if specified
 * @property int $recursive - recursively delete all contents inside this category or move contents to newParentId
 */
class CategoryRemoveRule extends Rule
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'newparent',
            'recursive',
        ];
    }
}