<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $scale
 */
class Outcome extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'scale',
        ];
    }
}