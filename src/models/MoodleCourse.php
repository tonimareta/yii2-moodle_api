<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\RestModel;

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
 * @property array $summaryfiles
 * @property array $overviewfiles
 * @property string $showactivitydates
 * @property string $showcompletionconditions
 * @property array $contacts
 * @property array $enrollmentmethods
 * @property array $customfields
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
 * @property array $filters
 * @property array $courseformatoptions
 */
class MoodleCourse extends RestModel
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
        ];
    }

    /**
     * @param int $categoryId
     * @return MoodleCourse[]
     */
    public static function getByCategory(int $categoryId): array
    {
        return static::getByField('category', $categoryId);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param bool $reset
     * @return MoodleCourse[]
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
     * @return MoodleCourse|null
     */
    public static function getByFieldOne(string $field, int|string $value): ?static
    {
        $courses = static::getByField($field, $value);
        return $courses[0] ?? null;
    }

    /**
     * @param int $id
     * @return MoodleCourse|null
     */
    public static function getById(int $id): ?static
    {
        return static::getByFieldOne('id', $id);
    }

    /**
     * @param int $idNumber
     * @return MoodleCourse|null
     */
    public static function getByIdNumber(int $idNumber): ?static
    {
        return static::getByFieldOne('idnumber', $idNumber);
    }

    /**
     * @param array $ids
     * @return MoodleCourse[]
     */
    public static function getByIds(array $ids): array
    {
        return static::getByField('ids', implode(',', $ids));
    }

    /**
     * @param string $shortname
     * @return MoodleCourse|null
     */
    public static function getByShortname(string $shortname): ?static
    {
        return static::getByFieldOne('shortname', $shortname);
    }

    /**
     * @param array $dataset
     * @return MoodleCourse[]
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
     * @return MoodleCourse[]
     */
    public static function searchByName(string $name): array
    {
        $courses = static::connect('core_course_search_courses', [
            'criterianame' => 'search',
            'criteriavalue' => $name,
        ]);

        return static::loadDataMultiple($courses);
    }
}