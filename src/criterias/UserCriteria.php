<?php

namespace tonimareta\moodle\criterias;

use tonimareta\moodle\Criteria;

/**
 * @property int $id - matching user id,
 * @property string $lastname - user last name (Note: you can use % for searching but it may be considerably slower!),
 * @property string $firstname - user first name (Note: you can use % for searching but it may be considerably slower!),
 * @property int|string $idnumber - matching user idnumber,
 * @property string $username - matching user username,
 * @property string $email - user email (Note: you can use % for searching but it may be considerably slower!),
 * @property string $auth - matching user auth plugin
 */
class UserCriteria extends Criteria
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'lastname',
            'firstname',
            'idnumber',
            'username',
            'email',
            'auth',
        ];
    }
}