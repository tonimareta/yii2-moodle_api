<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $filter
 * @property int $localstate
 * @property int $inheritedstate
 */
class CourseFilter extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'filter',
            'localstate',
            'inheritedstate',
        ];
    }
}