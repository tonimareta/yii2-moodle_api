<?php

namespace tonimareta\moodle\rules;

use tonimareta\moodle\objects\CustomField;
use tonimareta\moodle\objects\Preference;
use tonimareta\moodle\Rule;

/**
 * @property int $id - ID of user (used ont update only)
 * @property int $createpassword - True if password should be created and mailed to user.
 * @property string $username - Username policy is defined in Moodle security config.
 * @property string $auth - Auth plugins include manual, ldap, etc
 * @property string $password - Plain text password consisting of any characters
 * @property string $firstname - The first name(s) of the user
 * @property string $lastname - The family name of the user
 * @property string $email - A valid and unique email address
 * @property int $maildisplay - Email visibility
 * @property string $city - Home city of the user
 * @property string $country - Home country code of the user, such as AU or CZ
 * @property string $timezone - Timezone code such as Australia/Perth, or 99 for default
 * @property string $description - User profile description, no HTML
 * @property string $firstnamephonetic - The first name(s) phonetically of the user
 * @property string $lastnamephonetic - The family name phonetically of the user
 * @property string $middlename - The middle name of the user
 * @property string $alternatename - The alternate name of the user
 * @property string $interests - User interests (separated by commas)
 * @property string $idnumber - An arbitrary ID code number perhaps from the institution
 * @property string $institution - institution
 * @property string $department - department
 * @property string $phone1 - Phone 1
 * @property string $phone2 - Phone 2
 * @property string $address - Postal address
 * @property string $lang - Language code such as "en", must exist on server
 * @property string $calendartype - Calendar type such as "gregorian", must exist on server
 * @property string $theme - Theme name such as "standard", must exist on server
 * @property int $mailformat - Mail format code is 0 for plain text, 1 for HTML etc
 * @property CustomField[] $customfields - User custom fields (also known as user profil fields)
 * @property Preference[] $preferences - User preferences
 */
class UserRule extends Rule
{
    /**
     * @return string[]
     */
    public function attributes(): array
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
}