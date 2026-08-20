<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $visible
 * @property string $summary
 * @property int $summaryformat
 * @property int $section
 * @property int $hiddenbynumsections
 * @property int $uservisible
 * @property string $availabilityinfo
 * @property string $component
 * @property int $itemid
 * @property CourseModule[] $modules
 */
class CourseSection extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'visible',
            'summary',
            'summaryformat',
            'section',
            'hiddenbynumsections',
            'uservisible',
            'availabilityinfo',
            'component',
            'itemid',
            'modules',
        ];
    }

    /**
     * @return array
     */
    protected function relations(): array
    {
        return [
            'modules' => CourseModule::class,
        ];
    }
}