<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\objects\ActivityBadge;
use tonimareta\moodle\objects\AdvancedGrading;
use tonimareta\moodle\objects\CompletionData;
use tonimareta\moodle\objects\File;
use tonimareta\moodle\objects\FileInfo;
use tonimareta\moodle\objects\MoodleDate;
use tonimareta\moodle\objects\Outcome;
use tonimareta\moodle\RestModel;
use tonimareta\moodle\rules\CourseCreateRule;
use tonimareta\moodle\rules\CourseUpdateRule;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

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
 * @property int $visibleold
 * @property int $visibleoncoursepage
 * @property int $showdescription
 * @property int $course
 * @property int $section
 * @property int $sectionnum
 * @property int $module
 * @property string $modicon
 * @property string $modname
 * @property string $idnumber
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
 * @property int $completiongradeitemnumber
 * @property int $completionpassgrade
 * @property int $completionview
 * @property int $completionexpected
 * @property int $downloadcontent
 * @property MoodleDate[] $dates
 * @property int $groupmode
 * @property int $groupingid
 * @property File[] $contents
 * @property FileInfo $contentsinfo
 * @property int $added
 * @property int $score
 * @property string $gradepass
 * @property int $gradecat
 * @property AdvancedGrading[] $advancedgrading
 * @property Outcome[] $outcomes
 */
class CourseModule extends RestModel
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
            'visibleold',
            'visibleoncoursepage',
            'showdescription',
            'course',
            'section',
            'sectionnum',
            'module',
            'modicon',
            'modname',
            'idnumber',
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
            'completiongradeitemnumber',
            'completionpassgrade',
            'completionview',
            'completionexpected',
            'downloadcontent',
            'dates',
            'groupmode',
            'groupingid',
            'contents',
            'contentsinfo',
            'added',
            'score',
            'gradepass',
            'gradecat',
            'advancedgrading',
            'outcomes',
        ];
    }

    /**
     * @param int $id
     * @return static|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getById(int $id): ?static
    {
        if (!$module = static::connect('core_course_get_course_module', ['cmid' => $id])) {
            return null;
        }

        $module = $module['cm'] ?? $module;
        return new static($module);
    }

    /**
     * @param string $moduleName
     * @param int $instanceId
     * @return static|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByInstanceId(string $moduleName, int $instanceId): ?static
    {
        $module = static::connect('core_course_get_course_module_by_instance', [
            'module' => $moduleName,
            'instance' => $instanceId,
        ]);

        if (!$module) {
            return null;
        }

        $module = $module['cm'] ?? $module;
        return new static($module);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [[
                'id', 'instance', 'contextid', 'visible', 'uservisible', 'visibleold', 'visibleoncoursepage',
                'showdescription', 'course', 'section', 'sectionnum', 'module', 'branded', 'indent', 'noviewlink',
                'candisplay', 'completion', 'completiongradeitemnumber', 'completionpassgrade', 'completionview',
                'completionexpected', 'downloadcontent', 'groupmode', 'groupingid', 'added', 'score', 'gradecat',
            ], 'integer'],
            [[
                'url', 'name', 'description', 'availabilityinfo', 'modicon', 'modname', 'idnumber', 'purpose',
                'modplural', 'availability', 'onclick', 'afterlink', 'customdata', 'gradepass',
            ], 'string'],
            [[

            ]],
            [[
                'activitybadge', 'completiondata', 'dates', 'contents', 'contentsinfo', 'advancedgrading', 'outcomes',
            ], 'safe'],
        ];
    }

    /**
     * @return array[]
     */
    protected function crudRules(): array
    {
        return [
            self::SCENARIO_CREATE => [],
            self::SCENARIO_DELETE => ['core_course_delete_modules', ['cmids' => [$this->id]]],
            self::SCENARIO_UPDATE => [],
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
            'advancedgrading' => AdvancedGrading::class,
            'outcomes' => Outcome::class,
        ];
    }
}