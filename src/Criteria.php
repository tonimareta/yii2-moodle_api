<?php

namespace tonimareta\moodle;

use tonimareta\moodle\interfaces\CriteriaInterface;

class Criteria extends Option implements CriteriaInterface
{
    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return ['key', 'value'];
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return [];
    }
}