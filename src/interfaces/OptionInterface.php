<?php

namespace tonimareta\moodle\interfaces;

interface OptionInterface
{
    /**
     * @return array
     */
    public function getItems(): array;
}