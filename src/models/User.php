<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\objects\CustomField;
use tonimareta\moodle\objects\Group;
use tonimareta\moodle\objects\Role;
use tonimareta\moodle\objects\Preference;
use tonimareta\moodle\RestModel;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 * @property int $id
 * @property string $auth
 * @property string $password
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property string $fullname
 * @property string $initials
 * @property string $email
 * @property int $maildisplay
 * @property int $mailformat
 * @property string $address
 * @property string $phone1
 * @property string $phone2
 * @property string $department
 * @property string $institution
 * @property string $idnumber
 * @property string $interests
 * @property int $firstaccess
 * @property int $lastaccess
 * @property int $lastcourseaccess
 * @property string $description
 * @property int $descriptionformat
 * @property string $city
 * @property string $country
 * @property string $firstnamephonetic
 * @property string $lastnamephonetic
 * @property string $alternatename
 * @property string $profileimageurlsmall
 * @property string $profileimageurl
 * @property string $timezone
 * @property string $lang
 * @property string $calendartype
 * @property string $theme
 * @property CustomField[] $customfields
 * @property Group[] $groups
 * @property Role[] $roles
 * @property Preference[] $preferences
 * @property Course[] $enrolledcourses
 */
class User extends RestModel
{
    /**
     * True if password should be created and mailed to user.
     *
     * @var bool
     */
    public bool $createpassword = false;

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'auth',
            'username',
            'password',
            'firstname',
            'lastname',
            'middlename',
            'fullname',
            'initials',
            'email',
            'maildisplay',
            'mailformat',
            'address',
            'phone1',
            'phone2',
            'department',
            'institution',
            'idnumber',
            'interests',
            'firstaccess',
            'lastaccess',
            'lastcourseaccess',
            'description',
            'descriptionformat',
            'city',
            'country',
            'firstnamephonetic',
            'lastnamephonetic',
            'alternatename',
            'profileimageurlsmall',
            'profileimageurl',
            'timezone',
            'lang',
            'calendartype',
            'theme',
            'customfields',
            'groups',
            'roles',
            'preferences',
            'enrolledcourses',
        ];
    }

    /**
     * @param array $conditions
     * @return static[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function findAll(array $conditions): array
    {
        $criteria = [];

        foreach ($conditions as $key => $value) {
            $criteria[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return parent::findAll(['criteria' => $criteria]);
    }

    /**
     * @param string $authType
     * @return User[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByAuthType(string $authType): array
    {
        return static::findAll(['auth' => $authType]);
    }

    /**
     * @param int $id
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getById(int $id): ?User
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param int|string $idNumber
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIdNumber(int|string $idNumber): ?User
    {
        return static::findOne(['idnumber' => $idNumber]);
    }

    /**
     * @param string $username
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @param string $email
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByEmail(string $email): ?User
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param string $email
     * @return User[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function searchByEmail(string $email): array
    {
        if (!str_contains($email, '%')) {
            $email = "%$email%";
        }

        return static::findAll(['email' => $email]);
    }

    /**
     * @param string $lastname
     * @param string|null $firstname
     * @param bool $strict
     * @return User[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function searchByName(string $lastname, ?string $firstname = null, bool $strict = false): array
    {
        if (!$strict) {
            if (!str_contains($lastname, '%')) {
                $lastname = "%$lastname%";
            }

            if ($firstname && !str_contains($firstname, '%')) {
                $firstname = "%$firstname%";
            }
        }

        return static::findAll(array_filter([
            'lastname' => $lastname,
            'firstname' => $firstname,
        ]));
    }

    /**
     * @return string[]
     */
    public function relations(): array
    {
        return [
            'customfields' => CustomField::class,
            'groups' => Group::class,
            'roles' => Role::class,
            'preferences' => Preference::class,
            'enrolledcourses' => Course::class,
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'firstname', 'lastname', 'email'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],
            [['id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id', 'firstaccess', 'lastaccess', 'lastcourseaccess', 'descriptionformat'], 'integer'],
            [[
                'username', 'firstname', 'lastname', 'initials', 'fullname', 'email', 'address', 'phone1', 'phone2',
                'department', 'institution', 'idnumber', 'interests', 'description', 'city', 'country',
                'profileimageurlsmall', 'profileimageurl',
            ], 'string'],
            [['customfields', 'groups', 'roles', 'preferences', 'enrolledcourses', 'createpassword'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function saveAttributes(): array
    {
        return [
            'id',
            'createpassword',
            'username',
            'auth',
            'password',
            'firstname',
            'lastname',
            'email',
            'maildisplay',
            'city',
            'country',
            'timezone',
            'description',
            'firstnamephonetic',
            'lastnamephonetic',
            'middlename',
            'alternatename',
            'interests',
            'idnumber',
            'institution',
            'department',
            'phone1',
            'phone2',
            'address',
            'lang',
            'calendartype',
            'theme',
            'mailformat',
            'customfields',
            'preferences',
        ];
    }

    /**
     * @return false[]
     */
    public static function services(): array
    {
        return [
            self::SCENARIO_CREATE => ['core_user_create_users'],
            self::SCENARIO_FIND => ['core_user_get_users'],
            self::SCENARIO_DELETE => ['core_user_delete_users', 'userids'],
            self::SCENARIO_UPDATE => ['core_user_update_users'],
        ];
    }
}