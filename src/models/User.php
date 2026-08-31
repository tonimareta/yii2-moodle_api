<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\interfaces\CriteriaInterface;
use tonimareta\moodle\interfaces\OptionInterface;
use tonimareta\moodle\objects\CustomField;
use tonimareta\moodle\objects\Group;
use tonimareta\moodle\objects\Role;
use tonimareta\moodle\objects\Preference;
use tonimareta\moodle\criterias\UserCriteria;
use tonimareta\moodle\RestModel;
use tonimareta\moodle\rules\UserRule;
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
     * @param string $authType
     * @return User[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByAuthType(string $authType): array
    {
        return static::getByCriteria(new UserCriteria(['auth' => $authType]));
    }

    /**
     * @param UserCriteria $criteria
     * @param OptionInterface|null $options - not used
     * @return User[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByCriteria(CriteriaInterface $criteria, ?OptionInterface $options = null): array
    {
        $users = static::connect('core_user_get_users', ['criteria' => $criteria->getItems()]);
        return static::loadData($users, 'users');
    }

    /**
     * @param int $id
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getById(int $id): ?User
    {
        return static::getByCriteriaOne(new UserCriteria(['id' => $id]));
    }

    /**
     * @param int|string $idNumber
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIdNumber(int|string $idNumber): ?User
    {
        return static::getByCriteriaOne(new UserCriteria(['idnumber' => $idNumber]));
    }

    /**
     * @param string $username
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByUsername(string $username): ?User
    {
        return static::getByCriteriaOne(new UserCriteria(['username' => $username]));
    }

    /**
     * @param string $email
     * @return User|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByEmail(string $email): ?User
    {
        return static::getByCriteriaOne(new UserCriteria(['email' => $email]));
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

        return static::getByCriteria(new UserCriteria(['email' => $email]));
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

        return static::getByCriteria(new UserCriteria([
            'lastname' => $lastname,
            'firstname' => $firstname,
        ]));
    }

    /**
     * @return false[]
     */
    protected function crudRules(): array
    {
        $rule = new UserRule($this->toArray());
        $params = $rule->filterItems();

        return [
            self::SCENARIO_CREATE => ['core_user_create_users', ['users' => [$params]]],
            self::SCENARIO_DELETE => ['core_user_delete_users', ['userids' => [$this->id]]],
            self::SCENARIO_UPDATE => ['core_user_update_users', ['users' => [$params]]],
        ];
    }

    /**
     * @return string[]
     */
    protected function relations(): array
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
}