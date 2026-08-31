<?php

namespace tonimareta\moodle\interfaces;

interface CriteriaInterface extends OptionInterface
{
    /**
     * @return array
     */
    public function options(): array;
}