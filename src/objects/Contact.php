<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $id
 * @property string $fullname
 */
class Contact extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'fullname',
        ];
    }
}