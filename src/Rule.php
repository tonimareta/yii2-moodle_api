<?php

namespace tonimareta\moodle;

class Rule extends Model
{
    /**
     * @return array
     */
    public function filterItems(): array
    {
        return array_filter($this->toArray());
    }
}