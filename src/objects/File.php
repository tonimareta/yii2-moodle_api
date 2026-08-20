<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $filename
 * @property string $filepath
 * @property int $filesize
 * @property string $fileurl
 * @property string $content
 * @property int $timecreated
 * @property int $timemodified
 * @property int $sortorder
 * @property string $type
 * @property string $mimetype
 * @property int $isexternalfile
 * @property string $repositorytype
 * @property int $userid
 * @property string $author
 * @property string $license
 * @property string $icon
 * @property FileTag[] $tags
 */
class File extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'filename',
            'filepath',
            'filesize',
            'fileurl',
            'content',
            'timecreated',
            'timemodified',
            'sortorder',
            'type',
            'mimetype',
            'isexternalfile',
            'repositorytype',
            'userid',
            'author',
            'license',
            'icon',
            'tags',
        ];
    }

    /**
     * @return string[]
     */
    protected function relations(): array
    {
        return [
            'tags' =>FileTag::class,
        ];
    }
}