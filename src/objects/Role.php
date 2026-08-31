<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $roleid
 * @property string $name
 * @property string $shortname
 * @property int $sortorder
 */
class Role extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'roleid',
            'name',
            'shortname',
            'sortorder',
        ];
    }
}