<?php

namespace tonimareta\moodle\options;

use tonimareta\moodle\Option;

/**
 * @property string $withcapability - return only users with this capability (requires 'moodle/role:review')
 * @property integer $groupid - return only users in this group id (requires 'moodle/site:accessallgroups')
 * @property integer $onlyactive - return only users with active enrolments and matching time restrictions (requires 'moodle/course:enrolreview')
 * @property integer $onlysuspended - return only suspended users (requires 'moodle/course:enrolreview')
 * @property string[] $userfields - return only the values of these user fields.
 * @property integer $limitfrom - sql limit from.
 * @property integer $limitnumber - maximum number of returned users.
 * @property string $sortby - sort by id, firstname or lastname. For ordering like the site does, use siteorder.
 * @property string $sortdirection - ASC or DESC
 */
class EnrolOption extends Option
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'withcapability',
            'groupid',
            'onlyactive',
            'onlysuspended',
            'userfields',
            'limitfrom',
            'limitnumber',
            'sortby',
            'sortdirection',
        ];
    }

    /**
     * @param array $config
     * @return void
     */
    protected function configure(array $config): void
    {
        parent::configure($config);

        if ($this->onlyactive) {
            $this->onlysuspended = null;
        }

        if ($this->onlysuspended) {
            $this->onlyactive = null;
        }
    }
}