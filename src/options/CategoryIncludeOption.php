<?php

namespace tonimareta\moodle\options;

use tonimareta\moodle\Option;

/**
 * @property int $addsubcategories
 */
class CategoryIncludeOption extends Option
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'addsubcategories',
        ];
    }
}