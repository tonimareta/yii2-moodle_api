<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $name
 * @property string $value
 */
class Preference extends Model
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