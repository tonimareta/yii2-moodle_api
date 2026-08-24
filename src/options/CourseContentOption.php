<?php

namespace tonimareta\moodle\options;

use tonimareta\moodle\Option;

/**
 * @property bool $excludemodules - Do not return modules, return only the sections structure
 * @property bool $excludecontents - Do not return module contents (i.e: files inside a resource)
 * @property bool $includestealthmodules - Return stealth modules for students in a special section (with id -1)
 * @property int $sectionid - Return only this section
 * @property int $sectionnumber - Return only this section with number (order)
 * @property int $cmid - Return only this module information (among the whole sections structure)
 * @property string $modname - Return only modules with this name "label, forum, etc..."
 * @property int $modid - Return only the module with this id
 */
class CourseContentOption extends Option
{
    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'excludemodules',
            'excludecontents',
            'sectionid',
            'sectionnumber',
            'cmid',
            'modname',
            'modid',
        ];
    }
}