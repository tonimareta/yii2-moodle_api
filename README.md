# Moodle API for Yii2

Contains models for working with the Moodle web services.

To enable web services in your Moodle installation use [official documentation](https://docs.moodle.org/500/en/Using_web_services)

## Configuration

Add to `composer.json`:

```json
{
    "require": {
        "tonimareta/moodle": "dev-main"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/tonimareta/yii2-moodle_api"
        }
    ]
}
```

Run `composer update`.

Add to `components` section in `config/web.php`:

```php
'moodleAPI' => [
    'class' => \tonimareta\moodle\Connection::class,
    'url' => '<your_moodle_site_url>',
    'token' => '<your_moodle_web_service_token>',
    'format' => 'json' // or xml (optional, default: json)
],
```


## Requirements

* **Moodle Version:** 4.5+
* **PHP Version:** 8.1+

## Table of Contents
1. [Models](#models)
   - [Course](#course)
   - [Course Category](#course-category)
   - [Course Module](#course-module)
   - [Course Section](#course-section)
   - [Enrolment](#enrolment)
   - [User](#user)
2. [Objects](#objects)
3. [Options](#options)

# Models
## Course
```php
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
use tonimareta\moodle\models\Course;
```

### Get course by id
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getById(int $id): Course|null;
```

### Get courses by ids
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByIds(array $ids): Course[];
```

### Get course by shortname
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByShortname(string $shortname): Course|null;
```

### Get course by idnumber
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByIdNumber(int $idNumber): Course|null;
```

### Get courses for category
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByCategory(int $category): Course[];
```

### Search courses by name
```php
// Moodle web service function: core_course_search_courses
Course::searchByName(string $name): Course[];
```

### Get course category
```php
// Moodle web service function: core_course_get_categories
$course = Course::getById($id);
$course->getCategory(): Category|null;
```

### Get course contents (sections with modules)
```php
// Moodle web service function: core_course_get_contents
$course = Course::getById($id);
$course->getContent(CourseContentOption|null $options = null): CourseSection[];
```

### Get course enrolled users
```php
// Moodle web service function: core_enrol_get_enrolled_users
$course = Course::getById($id);
$course->getContent(EnrolOption|null $options = null): User[];
```

### Enrol user to course
```php
// Moodle web service function: enrol_manual_enrol_users
$course = Course::getById($id);
$course->enrolUser(int $userId, int $roleId, int|null $timeStart = null, int|null $timeEnd = null): bool
```

### Unenrol user to course
```php
// Moodle web service function: enrol_manual_unenrol_users
$course = Course::getById($id);
$course->unEnrolUser(int $userId): bool
```

### Create course
```php
// Moodle web service function: core_course_create_courses
$course = new Course([
    'fullname' => $fullname, 
    'shortname' => $shortname, 
    'categoryid' => $categoryid,
]);
$course->save(): bool;
```

### Delete course
```php
// Moodle web service function: core_course_delete_courses
$course = Course::getById($id);
$course->delete(): bool;
```

### Update course
```php
// Moodle web service function: core_course_update_courses
$course = Course::getById($id);
$course->fullname = $newName;
$course->save(): bool;
```

## Course Category

```php
/**
 * @property int $ids
 * @property string $name
 * @property string $idnumber
 * @property string $description
 * @property int $descriptionformat
 * @property int $parent
 * @property int $sortorder
 * @property int $coursecount
 * @property int $visible
 * @property int $visibleold
 * @property int $timemodified
 * @property int $depth
 * @property string $path
 * @property string $theme
 */
use tonimareta\moodle\models\Category;
```

### Get category by id
```php
// Moodle web service function: core_course_get_categories
Category::getById(int $id): Category|null;
```

### Get categories by ids
```php
// Moodle web service function: core_course_get_categories
Category::getByIds(array $ids): Category[];
```

### Get categories by name
```php
// Moodle web service function: core_course_get_categories
Category::getByName(string $name): Category[];
```

### Get categories by parent id
```php
// Moodle web service function: core_course_get_categories
Category::getByParentId(int $parentId): Category[];
```

### Get category by idnumber
```php
// Moodle web service function: core_course_get_categories
Category::getByIdnumber(string $idnumber): Category|null;
```

### Get hidden categories
```php
// Moodle web service function: core_course_get_categories
Category::getHidden(): Category[];
```

### Get visible categories
```php
// Moodle web service function: core_course_get_categories
Category::getByVisibility(bool $visible = true, int|null $parent = null): Category[];
```

### Create category
```php
// Moodle web service function: core_course_create_categories
$category = new Category(['name' => $name, 'parent' => $parentId]);
$category->create(): Category|null;
```

### Delete category
```php
// Moodle web service function: core_course_delete_categories
$category = Category::getById($id);
$category->delete(): bool;
```

### Update category
```php
// Moodle web service function: core_course_update_categories
$category = Category::getById($id);
$category->name = $newName;
$category->update(): bool;
```

## Course Module
```php
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
use tonimareta\moodle\models\CourseModule;
```

### Get course module by id
```php
// Moodle web service function: core_course_get_course_module
CourseModule::getById(int $id): CourseModule|null;
```

### Get course module by instance id
```php
// Moodle web service function: core_course_get_course_module_by_instance
CourseModule::getByInstanceId(string $moduleName, int $instanceId): CourseModule|null;
```

### Delete module
```php
// Moodle web service function: core_course_delete_modules
$module = CourseModule::getById($id);
$module->delete(): bool;
```

## Course Section
```php
/**
 * @property int $id
 * @property string $name
 * @property int $visible
 * @property string $summary
 * @property int $summaryformat
 * @property int $section
 * @property int $hiddenbynumsections
 * @property int $uservisible
 * @property string $availabilityinfo
 * @property string $component
 * @property int $itemid
 * @property CourseModule[] $modules
 */
use tonimareta\moodle\models\CourseSection;
```

## Enrolment

```php
/**
 * @property int $courseid
 * @property int $userid
 * @property int $roleid
 * @property int $timestart
 * @property int $timeend
 * @property int $suspend
 */
use tonimareta\moodle\models\Enrolment;
```

### Enrol users to courses
```php
// Moodle web service function: enrol_manual_enrol_users
Enrolment::massEnrol(Course[] $courses, Users[] $users, int|null $timeStart = null, int|null $timeEnd = null): bool;
```

### Removes manual enrolment
```php
// Moodle web service function: enrol_manual_unenrol_users
Enrolment::massUnEnrol(Course[] $courses, Users[] $users): bool;
```

## User
```php
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
use tonimareta\moodle\models\User;
```

### Get users by auth type
```php
// Moodle web service function: core_user_get_users
User::getByAuthType(string $authType): User[];
```

### Get user by id
```php
// Moodle web service function: core_user_get_users
User::getById(int $id): User|null;
```

### Get user by idnumber
```php
// Moodle web service function: core_user_get_users
User::getByIdNumber(int|string $idNumber): User|null;
```

### Get user by email
```php
// Moodle web service function: core_user_get_users
User::getByEmail(string $email): User|null;
```

### Get user by username
```php
// Moodle web service function: core_user_get_users
User::getByUsername(string $username): User|null;
```

### Search users by email
```php
// Moodle web service function: core_user_get_users
User::searchByEmail(string $email): array;
```

### Search users by full name
```php
// Moodle web service function: core_user_get_users
User::searchByName(string $lastname, string|null $firstname = null, bool $strict = false): array;
```

### Create user
```php
// Moodle web service function: core_user_create_users
$user = new User([
    'username' => $username, 
    'firstname' => $firstname, 
    'lastname' => $lastname, 
    'email' => $email,
]);
$user->save(): bool;
```

### Delete module
```php
// Moodle web service function: core_user_delete_users
$user = User::getById($id);
$user->delete(): bool;
```

### Update user
```php
// Moodle web service function: core_user_update_users
$user = User::getById($id);
$user->lastname = $lastname;
$user->firstname = $firstname;
$user->save(): bool;
```

# Objects
## ActivityBadge
```php
 * @property string $badgecontent
 * @property string $badgestyle
 * @property string $badgeurl
 * @property string $badgeelementid
 * @property BadgeExtraAttributesOption[] $badgeextraattributes
```

## AdvancedGrading
```php
 * @property string $area
 * @property string $method
```

## BadgeExtraAttribute
```php
 * @property string $name
 * @property mixed $value
```

## CompletionData
```php
 * @property int $state 
 * @property int $timecompleted 
 * @property int $overrideby 
 * @property int $valueused 
 * @property int $hascompletion 
 * @property int $isautomatic 
 * @property int $istrackeduser 
 * @property int $uservisible 
 * @property CompletionDetail[] $details 
 * @property int $isoverallcomplete 
```

## CompletionDetail
```php
 * @property string $rulename
 * @property CompletionRule $rulevalue
```

## CompletionRule
```php
 * @property int $status
 * @property string $description
```

## Contact
```php
 * @property int $id
 * @property string $fullname
```

## CourseFilter
```php
 * @property string $filter
 * @property int $localstate
 * @property int $inheritedstate
```

## CustomField
```php
 * @property string $name
 * @property string $shortname
 * @property string $type
 * @property string $valueraw
 * @property string $value
```

## File
```php
 * @property string $filename
 * @property string $filepath
 * @property int $filesize
 * @property string $fileurl
 * @property string $content
 * @property int $timecreated
 * @property int $timemodified
 * @property int $sortorder
 * @property string $type
 * @property string $mimetype
 * @property int $isexternalfile
 * @property string $repositorytype
 * @property int $userid
 * @property string $author
 * @property string $license
 * @property string $icon
 * @property FileTag[] $tags
```

## FileInfo
```php
 * @property int $filescount
 * @property int $filessize
 * @property int $lastmodified
 * @property array $mimetypes
 * @property string $repositorytype
```

## FileTag
```php
 * @property int $id
 * @property string $name
 * @property string $rawname
 * @property int $isstandard
 * @property int $tagcollid
 * @property int $taginstanceid
 * @property int $taginstancecontextid
 * @property int $itemid
 * @property int $ordering
 * @property int $flag
 * @property string $viewurl
```

## Group
```php
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $descriptionformat
```

## MoodleDate
```php
 * @property string $label
 * @property int $timestamp
 * @property int $relativeto
 * @property string $dataid
```

## Outcome
```php
 * @property string $id
 * @property string $name
 * @property string $scale
```

## Preference
```php
 * @property string $name
 * @property string $value
```

## Role
```php
 * @property int $roleid
 * @property string $name
 * @property string $shortname
 * @property int $sortorder
```

# Options
## CourseContentOption
```php
 * @property bool $excludemodules - Do not return modules, return only the sections structure
 * @property bool $excludecontents - Do not return module contents (i.e: files inside a resource)
 * @property bool $includestealthmodules - Return stealth modules for students in a special section (with id -1)
 * @property int $sectionid - Return only this section
 * @property int $sectionnumber - Return only this section with number (order)
 * @property int $cmid - Return only this module information (among the whole sections structure)
 * @property string $modname - Return only modules with this name "label, forum, etc..."
 * @property int $modid - Return only the module with this id
```

## CourseFormatOption
```php
 * @property int $hiddensections
 * @property int $coursedisplay
 * @property int $indentation
```

## EnrolOption
```php
 * @property string $withcapability - return only users with this capability (requires 'moodle/role:review')
 * @property integer $groupid - return only users in this group id (requires 'moodle/site:accessallgroups')
 * @property integer $onlyactive - return only users with active enrolments and matching time restrictions (requires 'moodle/course:enrolreview')
 * @property integer $onlysuspended - return only suspended users (requires 'moodle/course:enrolreview')
 * @property string[] $userfields - return only the values of these user fields.
 * @property integer $limitfrom - sql limit from.
 * @property integer $limitnumber - maximum number of returned users.
 * @property string $sortby - sort by id, firstname or lastname. For ordering like the site does, use siteorder.
 * @property string $sortdirection - ASC or DESC
```
