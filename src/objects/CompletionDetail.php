<?php

namespace tonimareta\moodle\objects;

use tonimareta\moodle\Model;

/**
 * @property string $rulename
 * @property CompletionRule $rulevalue
 */
class CompletionDetail extends Model
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'rulename',
            'rulevalue',
        ];
    }

    /**
     * @return array
     */
    protected function relations(): array
    {
        return [
            'rulevalue' => CompletionRule::class,
        ];
    }
}