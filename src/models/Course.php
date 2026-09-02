<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\objects\Contact;
use tonimareta\moodle\objects\CourseFilter;
use tonimareta\moodle\objects\CustomField;
use tonimareta\moodle\objects\File;
use tonimareta\moodle\options\CourseContentOption;
use tonimareta\moodle\options\CourseFormatOption;
use tonimareta\moodle\options\EnrolOption;
use tonimareta\moodle\RestModel;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\MethodNotAllowedHttpException;

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
 * @property int $showactivitydates
 * @property int $showcompletionconditions
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
     * @param int $userId
     * @param int $roleId
     * @param int|null $timeStart
     * @param int|null $timeEnd
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function enrolUser(int $userId, int $roleId, ?int $timeStart = null, ?int $timeEnd = null): bool
    {
        if (!$this->id) {
            return false;
        }

        $enrolment = new Enrolment([
            'courseid' => $this->id,
            'userid' => $userId,
            'roleid' => $roleId,
            'timestart' => $timeStart,
            'timeend' => $timeEnd,
        ]);

        return $enrolment->save();
    }

    /**
     * @param array $conditions
     * @return static[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function findAll(array $conditions): array
    {
        $field = array_key_first($conditions);
        $value = $conditions[$field];

        return parent::findAll([
            'field' => $field,
            'value' => $value,
        ]);
    }

    /**
     * @param int $categoryId
     * @return Course[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByCategory(int $categoryId): array
    {
        return static::findAll(['category' => $categoryId]);
    }

    /**
     * @param int $id
     * @return Course|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getById(int $id): ?static
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param int $idNumber
     * @return Course|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIdNumber(int $idNumber): ?static
    {
        return static::findOne(['idnumber' => $idNumber]);
    }

    /**
     * @param array $ids
     * @return Course[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIds(array $ids): array
    {
        return static::findAll(['ids' => implode(',', $ids)]);
    }

    /**
     * @param string $shortname
     * @return Course|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByShortname(string $shortname): ?static
    {
        return static::findOne(['shortname' => $shortname]);
    }

    /**
     * @return Category|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getCategory(): ?Category
    {
        if (!$this->categoryid) {
            return null;
        }

        return Category::getById($this->categoryid);
    }

    /**
     * @param CourseContentOption|null $options
     * @return CourseSection[]
     * @throws Exception
     * @throws InvalidConfigException
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
     * @param EnrolOption|null $options
     * @return array
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getUsers(?EnrolOption $options = null): array
    {
        if (!$this->id) {
            return [];
        }

        $users = static::connect('core_enrol_get_enrolled_users', array_filter([
            'courseid' => $this->id,
            'options' => $options?->getItems(),
        ]));

        return User::loadData($users);
    }

    /**
     * @param string $name
     * @return Course[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function searchByName(string $name): array
    {
        $courses = static::connect('core_course_search_courses', [
            'criterianame' => 'search',
            'criteriavalue' => $name,
        ]);

        return static::loadData($courses, 'courses');
    }

    /**
     * @return array
     */
    public function relations(): array
    {
        return [
            'summaryfiles' => File::class,
            'overviewfiles' => File::class,
            'filters' => CourseFilter::class,
            'courseformatoptions' => CourseFormatOption::class,
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],
            [['fullname', 'shortname', 'categoryid'], 'required', 'on' => self::SCENARIO_CREATE],

            [[
                'id', 'categoryid', 'sortorder', 'summaryformat', 'showgrades', 'newsitems', 'startdate', 'enddate',
                'maxbytes', 'showreports', 'visible', 'groupmode', 'groupmodeforce', 'defaultgroupingid',
                'enablecompletion', 'completionnotify', 'marker', 'legacyfiles', 'timecreated',
                'timemodified', 'requested', 'cacherev',
            ], 'integer'],
            [[
                'fullname', 'displayname', 'shortname', 'courseimage', 'categoryname', 'summary', 'idnumber', 'format',
                'lang', 'theme', 'calendartype', 'communicationroomname', 'communicationroomurl',
            ], 'string'],
            [[
                'enrollmentmethods', 'showactivitydates', 'showcompletionconditions', 'contacts', 'customfields',
                'filters', 'courseformatoptions', 'summaryfiles', 'overviewfiles', 'ids', 'category', 'sectionid',
            ], 'safe'],
        ];
    }

    /**
     * @return string[]
     */
    public function saveAttributes(): array
    {
        return [
            'fullname',
            'shortname',
            'categoryid',
            'idnumber',
            'summary',
            'summaryformat',
            'format',
            'showgrades',
            'newsitems',
            'startdate',
            'enddate',
            'numsections',
            'maxbytes',
            'showreports',
            'visible',
            'hiddensections',
            'groupmode',
            'groupmodeforce',
            'defaultgroupingid',
            'enablecompletion',
            'completionnotify',
            'lang',
        ];
    }

    /**
     * @return array[]
     */
    public static function services(): array
    {
        return [
            self::SCENARIO_CREATE => ['core_course_create_courses'],
            self::SCENARIO_DELETE => ['core_course_delete_courses', 'courseids'],
            self::SCENARIO_FIND => ['core_course_get_courses_by_field'],
            self::SCENARIO_UPDATE => ['core_course_update_courses'],
        ];
    }

    /**
     * @param int $userId
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function unEnrolUser(int $userId): bool
    {
        if (!$this->id) {
            return false;
        }

        $enrolment = new Enrolment(['courseid' => $this->id, 'userid' => $userId]);
        return $enrolment->delete();
    }
}