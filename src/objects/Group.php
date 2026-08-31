<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $descriptionformat
 */
class Group extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'description',
            'descriptionformat',
        ];
    }
}