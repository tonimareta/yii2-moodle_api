<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\RestModel;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\MethodNotAllowedHttpException;

/**
 * @property int $courseid
 * @property int $userid
 * @property int $roleid
 * @property int $timestart
 * @property int $timeend
 * @property int $suspend
 */
class Enrolment extends RestModel
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'courseid',
            'userid',
            'roleid',
            'timestart',
            'timeend',
            'suspend',
        ];
    }

    /**
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function delete(): bool
    {
        $this->setScenario(self::SCENARIO_DELETE);

        if (!$this->validate()) {
            return false;
        }

        $result = static::execute(self::SCENARIO_DELETE, [[
            'courseid' => $this->courseid,
            'userid' => $this->userid,
        ]]);

        if (!is_array($result) && empty($result)) {
            return false;
        }

        return true;
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
                        'roleid' => $role->roleid,
                        'timestart' => $timeStart,
                        'timeend' => $timeEnd,
                    ]);
                }
            }
        }

        return static::startEnrolments(self::SCENARIO_CREATE, $enrolments, $errors);
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

                $enrolments[] = new self([
                    'courseid' => $course->id,
                    'userid' => $user->id,
                ]);
            }
        }

        return static::startEnrolments(self::SCENARIO_DELETE, $enrolments, $errors);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['courseid', 'userid', 'roleid'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_DELETE]],
            [['courseid', 'userid', 'roleid', 'timestart', 'timeend', 'suspend',], 'integer'],
        ];
    }

    /**
     * @return array[]
     */
    public static function services(): array
    {
        return [
            self::SCENARIO_CREATE => ['enrol_manual_enrol_users'],
            self::SCENARIO_DELETE => ['enrol_manual_unenrol_users'],
        ];
    }

    /**
     * @param string $scenario
     * @param array $enrolments
     * @param array $errors
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     */
    protected static function startEnrolments(string $scenario, array $enrolments, array $errors = []): bool
    {
        $services = static::services();
        $method = $services[$scenario][0] ?? null;

        if (!$method) {
            $errors[] = "Enrolment '{$scenario}' has no method.";
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(PHP_EOL, $errors));
        }

        if (empty($enrolments)) {
            return false;
        }

        return (bool) static::connect($method, ['enrolments' => $enrolments]);
    }
}