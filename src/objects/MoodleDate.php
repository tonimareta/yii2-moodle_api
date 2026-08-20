<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $label
 * @property int $timestamp
 * @property int $relativeto
 * @property string $dataid
 */
class MoodleDate extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'label',
            'timestamp',
            'relativeto',
            'dataid',
        ];
    }
}