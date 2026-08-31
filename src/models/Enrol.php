<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\RestModel;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 * @property int $id
 * @property int $courseid
 * @property int $userid
 * @property int $roleid
 * @property int $timestart
 * @property int $timeend
 * @property int $suspend
 */
class Enrol extends RestModel
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'courseid',
            'userid',
            'roleid',
            'timestart',
            'timeend',
            'suspend',
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        if (!$this->courseid || !$this->userid || !$this->roleid) {
            return null;
        }

        return (int) implode('', [$this->courseid, $this->userid, $this->roleid]);
    }

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->id = $this->getId();
    }

    /**
     * @param array $data
     * @param string|null $formName
     * @return bool
     */
    public function load($data, ?string $formName = null): bool
    {
        $result = parent::load($data, $formName);
        $this->id = $this->getId();
        return $result;
    }

    /**
     * @param Course[] $courses
     * @param User[] $users
     * @param int|null $timeStart
     * @param int|null $timeEnd
     * @return bool
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function massEnrol(array $courses, array $users, ?int $timeStart = null, ?int $timeEnd = null): bool
    {
        return static::startEnrolments($courses, $users, $timeStart, $timeEnd);
    }

    /**
     * @param Course[] $courses
     * @param User[] $users
     * @return bool
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function massUnEnrol(array $courses, array $users): bool
    {
        return static::startEnrolments($courses, $users, null, null, true);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['courseid', 'userid', 'roleid'], 'required', 'on' => self::SCENARIO_CREATE],
            [['courseid', 'userid', 'roleid'], 'required', 'on' => self::SCENARIO_DELETE],
            [['id', 'courseid', 'userid', 'roleid', 'timestart', 'timeend', 'suspend',], 'integer'],
        ];
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     * @return void
     */
    public function setAttributes($values, $safeOnly = true): void
    {
        parent::setAttributes($values, $safeOnly);
        $this->id = $this->getId();
    }

    /**
     * @return false[]
     */
    protected function crudRules(): array
    {
        return [
            self::SCENARIO_CREATE => ['enrol_manual_enrol_users', ['enrolments' => [array_filter($this->toArray())]]],
            self::SCENARIO_DELETE => ['enrol_manual_unenrol_users' => ['enrolments' => [
                ['courseid' => $this->courseid, 'userid' => $this->userid, 'roleid' => $this->roleid],
            ]]],
            self::SCENARIO_UPDATE => [],
        ];
    }

    /**
     * @param Course[] $courses
     * @param User[] $users
     * @param int|null $timeStart
     * @param int|null $timeEnd
     * @param bool $unEnrol
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     */
    protected static function startEnrolments(array $courses, array $users, ?int $timeStart, ?int $timeEnd, bool $unEnrol = false): bool
    {
        $enrolments = [];
        $errors = [];

        foreach ($courses as $course) {
            if (empty($course->id)) {
                continue;
            }

            foreach ($users as $user) {
                if (empty($user->id)) {
                    continue;
                }

                if (empty($user->roles)) {
                    $errors[] = "User '{$user->id}' has no roles.";
                    continue;
                }

                foreach ($user->roles as $role) {
                    $enrolments[] = new self([
                        'courseid' => $course->id,
                        'userid' => $user->id,
                        'roleid' => $role->id,
                        'timestart' => $timeStart,
                        'timeend' => $timeEnd,
                    ]);
                }
            }
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(PHP_EOL, $errors));
        }

        if (empty($enrolments)) {
            return false;
        }

        $prefix = !$unEnrol ? '' : 'un';
        return (bool) static::connect("enrol_manual_{$prefix}enrol_users", ['enrolments' => $enrolments]);
    }
}