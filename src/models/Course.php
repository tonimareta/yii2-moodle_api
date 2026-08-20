<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\objects\Contact;
use tonimareta\moodle\objects\CourseFilter;
use tonimareta\moodle\objects\CourseSection;
use tonimareta\moodle\objects\CustomField;
use tonimareta\moodle\objects\File;
use tonimareta\moodle\options\CourseContentOption;
use tonimareta\moodle\options\CourseFormatOption;
use tonimareta\moodle\RestModel;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $fullname
 * @property string $displayname
 * @property string $shortname
 * @property string $courseimage
 * @property int $categoryid
 * @property string $categoryname
 * @property int $sortorder
 * @property string $summary
 * @property int $summaryformat
 * @property File[] $summaryfiles
 * @property File[] $overviewfiles
 * @property string $showactivitydates
 * @property string $showcompletionconditions
 * @property Contact[] $contacts
 * @property array $enrollmentmethods
 * @property CustomField[] $customfields
 * @property string $idnumber
 * @property string $format
 * @property int $showgrades
 * @property int $newsitems
 * @property int $startdate
 * @property int $enddate
 * @property int $maxbytes
 * @property int $showreports
 * @property int $visible
 * @property int $groupmode
 * @property int $groupmodeforce
 * @property int $defaultgroupingid
 * @property int $enablecompletion
 * @property int $completionnotify
 * @property string $lang
 * @property string $theme
 * @property int $marker
 * @property int $legacyfiles
 * @property string $calendartype
 * @property int $timecreated
 * @property int $timemodified
 * @property int $requested
 * @property int $cacherev
 * @property CourseFilter[] $filters
 * @property CourseFormatOption $courseformatoptions
 * @property string $communicationroomname
 * @property string $communicationroomurl
 */
class Course extends RestModel
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'fullname',
            'displayname',
            'shortname',
            'courseimage',
            'categoryid',
            'categoryname',
            'sortorder',
            'summary',
            'summaryformat',
            'summaryfiles',
            'overviewfiles',
            'showactivitydates',
            'showcompletionconditions',
            'contacts',
            'enrollmentmethods',
            'customfields',
            'idnumber',
            'format',
            'showgrades',
            'newsitems',
            'startdate',
            'enddate',
            'maxbytes',
            'showreports',
            'visible',
            'groupmode',
            'groupmodeforce',
            'defaultgroupingid',
            'enablecompletion',
            'completionnotify',
            'lang',
            'theme',
            'marker',
            'legacyfiles',
            'calendartype',
            'timecreated',
            'timemodified',
            'requested',
            'cacherev',
            'filters',
            'courseformatoptions',
            'communicationroomname',
            'communicationroomurl',
        ];
    }

    /**
     * @param int $categoryId
     * @return Course[]
     */
    public static function getByCategory(int $categoryId): array
    {
        return static::getByField('category', $categoryId);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param bool $reset
     * @return Course[]
     */
    public static function getByField(string $field, int|string $value, bool $reset = false): array
    {
        $courses = static::connect('core_course_get_courses_by_field', [
            'field' => $field,
            'value' => $value,
        ]);

        return static::loadDataMultiple($courses);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @return Course|null
     */
    public static function getByFieldOne(string $field, int|string $value): ?static
    {
        $courses = static::getByField($field, $value);
        return $courses[0] ?? null;
    }

    /**
     * @param int $id
     * @return Course|null
     */
    public static function getById(int $id): ?static
    {
        return static::getByFieldOne('id', $id);
    }

    /**
     * @param int $idNumber
     * @return Course|null
     */
    public static function getByIdNumber(int $idNumber): ?static
    {
        return static::getByFieldOne('idnumber', $idNumber);
    }

    /**
     * @param array $ids
     * @return Course[]
     */
    public static function getByIds(array $ids): array
    {
        return static::getByField('ids', implode(',', $ids));
    }

    /**
     * @param string $shortname
     * @return Course|null
     */
    public static function getByShortname(string $shortname): ?static
    {
        return static::getByFieldOne('shortname', $shortname);
    }

    /**
     * @param CourseContentOption|null $options
     * @return CourseSection[]
     */
    public function getContent(?CourseContentOption $options = null): array
    {
        if (!$this->id) {
            return [];
        }

        $conditions = ['courseid' => $this->id];

        if (!is_null($options)) {
            $conditions = ArrayHelper::merge($conditions, $options->getItems());
        }

        if (!$data = static::connect('core_course_get_contents', $conditions)) {
            return [];
        }

        return array_map(fn($section) => new CourseSection($section), $data);
    }

    /**
     * @param array $dataset
     * @return Course[]
     */
    public static function loadDataMultiple(array $dataset): array
    {
        if (empty($dataset)) {
            return [];
        }

        $dataset = $dataset['courses'] ?? $dataset;
        return parent::loadDataMultiple($dataset);
    }

    /**
     * @param string $name
     * @return Course[]
     */
    public static function searchByName(string $name): array
    {
        $courses = static::connect('core_course_search_courses', [
            'criterianame' => 'search',
            'criteriavalue' => $name,
        ]);

        return static::loadDataMultiple($courses);
    }

    /**
     * @return array
     */
    protected function relations(): array
    {
        return [
            'summaryfiles' => File::class,
            'overviewfiles' => File::class,
            'filters' => CourseFilter::class,
            'courseformatoptions' => CourseFormatOption::class,
        ];
    }
}