<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $filescount
 * @property int $filessize
 * @property int $lastmodified
 * @property array $mimetypes
 * @property string $repositorytype
 */
class FileInfo extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'filescount',
            'filessize',
            'lastmodified',
            'mimetypes',
            'repositorytype',
        ];
    }
}