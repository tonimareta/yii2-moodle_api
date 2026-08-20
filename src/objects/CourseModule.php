<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $id
 * @property string $url
 * @property string $name
 * @property int $instance
 * @property int $contextid
 * @property string $description
 * @property int $visible
 * @property int $uservisible
 * @property string $availabilityinfo
 * @property int $visibleoncoursepage
 * @property string $modicon
 * @property string $modname
 * @property string $purpose
 * @property int $branded
 * @property string $modplural
 * @property string $availability
 * @property int $indent
 * @property string $onclick
 * @property string $afterlink
 * @property ActivityBadge[] $activitybadge
 * @property string $customdata
 * @property int $noviewlink
 * @property int $candisplay
 * @property int $completion
 * @property CompletionData $completiondata
 * @property int $downloadcontent
 * @property MoodleDate[] $dates
 * @property int $groupmode
 * @property File[] $contents
 * @property FileInfo $contentsinfo
 */
class CourseModule extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'url',
            'name',
            'instance',
            'contextid',
            'description',
            'visible',
            'uservisible',
            'availabilityinfo',
            'visibleoncoursepage',
            'modicon',
            'modname',
            'purpose',
            'branded',
            'modplural',
            'availability',
            'indent',
            'onclick',
            'afterlink',
            'activitybadge',
            'customdata',
            'noviewlink',
            'candisplay',
            'completion',
            'completiondata',
            'downloadcontent',
            'dates',
            'groupmode',
            'contents',
            'contentsinfo',
        ];
    }

    /**
     * @return string[]
     */
    protected function relations(): array
    {
        return [
            'activitybadge' => ActivityBadge::class,
            'completiondata' => CompletionData::class,
            'dates' => MoodleDate::class,
            'contents' => File::class,
            'contentsinfo' => FileInfo::class,
        ];
    }
}