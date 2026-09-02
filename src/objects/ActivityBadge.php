<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $badgecontent
 * @property string $badgestyle
 * @property string $badgeurl
 * @property string $badgeelementid
 * @property BadgeExtraAttribute[] $badgeextraattributes
 */
class ActivityBadge extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'badgecontent',
            'badgestyle',
            'badgeurl',
            'badgeelementid',
            'badgeextraattributes',
        ];
    }

    /**
     * @return array
     */
    public function relations(): array
    {
        return [
            'badgeextraattributes' => BadgeExtraAttribute::class,
        ];
    }
}