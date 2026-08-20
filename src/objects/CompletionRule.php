<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $status
 * @property string $description
 */
class CompletionRule extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'status',
            'description',
        ];
    }
}