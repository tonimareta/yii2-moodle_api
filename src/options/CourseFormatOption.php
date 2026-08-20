<?php

namespace tonimareta\moodle\options;

use tonimareta\moodle\Option;

/**
 * @property int $hiddensections
 * @property int $coursedisplay
 * @property int $indentation
 */
class CourseFormatOption extends Option
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'hiddensections',
            'coursedisplay',
            'indentation',
        ];
    }
}