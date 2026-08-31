<?php

namespace tonimareta\moodle\criterias;

use tonimareta\moodle\Criteria;

/**
 * @property int $id - course id
 * @property string $ids - comma separated course ids
 * @property string $shortname - course short name
 * @property string $idnumber - course id number
 * @property int $category - category id the course belongs to
 * @property int $sectionid - section id that belongs to a course
 */
class CourseCriteria extends Criteria
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'ids',
            'shortname',
            'idnumber',
            'category',
            'sectionid',
        ];
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $items = parent::getItems();
        return $items[0] ?? [];
    }

    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return ['field', 'value'];
    }
}