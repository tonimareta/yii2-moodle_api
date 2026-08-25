<?php

namespace tonimareta\moodle\rules;

use yii\helpers\ArrayHelper;

/**
 * @property int $id
 */
class CourseUpdateRule extends CourseCreateRule
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(['id'], parent::attributes());
    }
}