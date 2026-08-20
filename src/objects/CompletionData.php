<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property int $state
 * @property int $timecompleted
 * @property int $overrideby
 * @property int $valueused
 * @property int $hascompletion
 * @property int $isautomatic
 * @property int $istrackeduser
 * @property int $uservisible
 * @property CompletionDetail[] $details
 * @property int $isoverallcomplete
 */
class CompletionData extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'state',
            'timecompleted',
            'overrideby',
            'valueused',
            'hascompletion',
            'isautomatic',
            'istrackeduser',
            'uservisible',
            'details',
            'isoverallcomplete',
        ];
    }

    /**
     * @return array
     */
    protected function relations(): array
    {
        return [
            'details' => CompletionDetail::class,
        ];
    }
}