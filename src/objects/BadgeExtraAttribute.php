<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $name
 * @property mixed $value
 */
class BadgeExtraAttribute extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'name',
            'value',
        ];
    }
}