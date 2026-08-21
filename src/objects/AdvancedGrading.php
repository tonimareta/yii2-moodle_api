<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $area
 * @property string $method
 */
class AdvancedGrading extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'area',
            'method',
        ];
    }
}