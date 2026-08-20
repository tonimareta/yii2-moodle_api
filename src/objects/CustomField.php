<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $name
 * @property string $shortname
 * @property string $type
 * @property string $valueraw
 * @property string $value
 */
class CustomField extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'name',
            'shortname',
            'type',
            'valueraw',
            'value',
        ];
    }
}