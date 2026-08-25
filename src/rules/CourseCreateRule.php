<?php

namespace tonimareta\moodle\rules;

use tonimareta\moodle\Rule;

/**
 * @property string $fullname
 * @property string $shortname
 * @property int $categoryid
 * @property string $idnumber
 * @property string $summary
 * @property int $summaryformat
 * @property string $format
 * @property int $showgrades
 * @property int $newsitems
 * @property int $startdate
 * @property int $enddate
 * @property int $numsections
 * @property int $maxbytes
 * @property int $showreports
 * @property int $visible
 * @property int $hiddensections
 * @property int $groupmode
 * @property int $groupmodeforce
 * @property int $defaultgroupingid
 * @property int $enablecompletion
 * @property int $completionnotify
 * @property string $lang
 */
class CourseCreateRule extends Rule
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'fullname',
            'shortname',
            'categoryid',
            'idnumber',
            'summary',
            'summaryformat',
            'format',
            'showgrades',
            'newsitems',
            'startdate',
            'enddate',
            'numsections',
            'maxbytes',
            'showreports',
            'visible',
            'hiddensections',
            'groupmode',
            'groupmodeforce',
            'defaultgroupingid',
            'enablecompletion',
            'completionnotify',
            'lang',
        ];
    }
}