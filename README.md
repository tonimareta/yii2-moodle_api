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
1. [REST Models](#rest-models)
   - [Course](#course)
   - [Course Category](#course-category)
   - [Course Module](#course-module)
   - [Course Section](#course-section)
   - [User](#user)
2. [Responses](#responses)
   - [Models](#models)
   - [Objects](#objects)
   - [Options](#options)
   - [Criterias](#criterias)
   - [CRUD Rules](#rules)

# REST Models
## Course
```php
use tonimareta\moodle\models\Course;
```

### Get course by field
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByCriteria(CourseCriteria $criteria): Course[];
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
$course->getCategory(): CourseCategory|null;
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

### Create course
```php
// Moodle web service function: core_course_create_courses
// CourseRule used for filter params
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
// CourseRule used for filter params
$course = Course::getById($id);
$course->fullname = $newName;
$course->save(): bool;
```

## Course Category
```php
use tonimareta\moodle\models\CourseCategory;
```

### Get categories by field

```php
// Moodle web service function: core_course_get_categories
// Use CategoryIncludeOption for additional params
CourseCategory::getByCriteria(CategoryCriteria $criteria): CourseCategory[];
```

### Get category by id
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getById(int $id): CourseCategory|null;
```

### Get categories by ids
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getByIds(array $ids): CourseCategory[];
```

### Get categories by name
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getByName(string $name): CourseCategory[];
```

### Get categories by parent id
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getByParentId(int $parentId): CourseCategory[];
```

### Get category by idnumber
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getByIdnumber(string $idnumber): CourseCategory|null;
```

### Get hidden categories
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getHidden(): CourseCategory[];
```

### Get visible categories
```php
// Moodle web service function: core_course_get_categories
CourseCategory::getVisible(): CourseCategory[];
```

### Create category
```php
// Moodle web service function: core_course_create_categories
// CategoryRule used for filter params
$category = new CourseCategory(['name' => $name, 'parent' => $parentId]);
$category->create(): CourseCategory|null;
```

### Delete category
```php
// Moodle web service function: core_course_delete_categories
$category = CourseCategory::getById($id);
$category->delete(): bool;
```

### Update category
```php
// Moodle web service function: core_course_update_categories
// CategoryRule used for filter params
$category = CourseCategory::getById($id);
$category->name = $newName;
$category->update(): bool;
```

## Course Module
```php
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
use tonimareta\moodle\models\CourseSection;
```

## Enrol
```php
use tonimareta\moodle\models\Enrol;
```

### Enrol users to courses
```php
// Moodle web service function: enrol_manual_enrol_users
Enrol::massEnrol(Course[] $courses, Users[] $users, ?int $timeStart = null, ?int $timeEnd = null): bool;
```

### Removes manual enrolment
```php
// Moodle web service function: enrol_manual_unenrol_users
Enrol::massUnEnrol(array $courses, array $users): bool;
```

## User
```php
use tonimareta\moodle\models\User;
```

### Get users by field
```php
// Moodle web service function: core_user_get_users
User::getByCriteria(UserCriteria $criteria): User[];
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
User::searchByName(string $lastname, ?string $firstname = null, bool $strict = false): array;
```

### Create user
```php
// Moodle web service function: core_user_create_users
// UserRule used for filter params
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
// UserRule used for filter params
$user = User::getById($id);
$user->lastname = $lastname;
$user->firstname = $firstname;
$user->save(): bool;
```

# Responses
## Models
### Course
```php
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
```

### Course Category
```php
 * @property int $id
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
```

### Course Module
```php
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
```

### Course Section
```php
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
```

### Enrol
```php
 * @property int $id
 * @property int $courseid
 * @property int $userid
 * @property int $roleid
 * @property int $timestart
 * @property int $timeend
 * @property int $suspend
```

### User
```php
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
```

## Objects
### ActivityBadge
```php
 * @property string $badgecontent
 * @property string $badgestyle
 * @property string $badgeurl
 * @property string $badgeelementid
 * @property BadgeExtraAttributesOption[] $badgeextraattributes
```

### AdvancedGrading
```php
 * @property string $area
 * @property string $method
```

### BadgeExtraAttribute
```php
 * @property string $name
 * @property mixed $value
```

### CompletionData
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

### CompletionDetail
```php
 * @property string $rulename
 * @property CompletionRule $rulevalue
```

### CompletionRule
```php
 * @property int $status
 * @property string $description
```

### Contact
```php
 * @property int $id
 * @property string $fullname
```

### CourseFilter
```php
 * @property string $filter
 * @property int $localstate
 * @property int $inheritedstate
```

### CustomField
```php
 * @property string $name
 * @property string $shortname
 * @property string $type
 * @property string $valueraw
 * @property string $value
```

### File
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

### FileInfo
```php
 * @property int $filescount
 * @property int $filessize
 * @property int $lastmodified
 * @property array $mimetypes
 * @property string $repositorytype
```

### FileTag
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

### Group
```php
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $descriptionformat
```

### MoodleDate
```php
 * @property string $label
 * @property int $timestamp
 * @property int $relativeto
 * @property string $dataid
```

### Outcome
```php
 * @property string $id
 * @property string $name
 * @property string $scale
```

### Preference
```php
 * @property string $name
 * @property string $value
```

### Role
```php
 * @property int $roleid
 * @property string $name
 * @property string $shortname
 * @property int $sortorder
```

## Options
### CategoryIncludeOption
```php
 * @property int $addsubcategories
```

### CourseContentOption
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

### CourseFormatOption
```php
 * @property int $hiddensections
 * @property int $coursedisplay
 * @property int $indentation
```

### EnrolOption
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

## Criterias
### CategoryCriteria
```php
 * @property int $id - the category id
 * @property string $ids - category ids separated by commas
 * @property string $name - the category name
 * @property int $parent - the parent category id
 * @property string $idnumber - category idnumber - user must have 'moodle/category:manage' to search on idnumber
 * @property int $visible - whether the returned categories must be visible or hidden
```

### CourseCriteria
```php
 * @property int $id - course id
 * @property string $ids - comma separated course ids
 * @property string $shortname - course short name
 * @property string $idnumber - course id number
 * @property int $category - category id the course belongs to
 * @property int $sectionid - section id that belongs to a course
```

### UserCriteria
```php
 * @property int $id - matching user id,
 * @property string $lastname - user last name (Note: you can use % for searching but it may be considerably slower!),
 * @property string $firstname - user first name (Note: you can use % for searching but it may be considerably slower!),
 * @property string $idnumber - matching user idnumber,
 * @property string $username - matching user username,
 * @property string $email - user email (Note: you can use % for searching but it may be considerably slower!),
 * @property string $auth - matching user auth plugin
```

## CRUD Rules
### CategoryRule
```php
 * @property string $name - category name
 * @property string $idnumber - category id number
 * @property int $parent - parent category id
 * @property string $description - category description
 * @property int $descriptionformat  - description format (1 = HTML, 0 = MOODLE, 2 = PLAIN, or 4 = MARKDOWN)
```

### CourseRule
```php
 * @property string $fullname - full name
 * @property string $shortname - course short name
 * @property int $categoryid - category id
 * @property string $idnumber - id number
 * @property string $summary - summary
 * @property int $summaryformat - summary format (1 = HTML, 0 = MOODLE, 2 = PLAIN, or 4 = MARKDOWN)
 * @property string $format - course format: weeks, topics, social, site,..
 * @property int $showgrades - 1 if grades are shown, otherwise 0
 * @property int $newsitems - number of recent items appearing on the course page
 * @property int $startdate - timestamp when the course start
 * @property int $enddate - timestamp when the course end
 * @property int $numsections - (deprecated, use courseformatoptions) number of weeks/topics
 * @property int $maxbytes - largest size of file that can be uploaded into the course
 * @property int $showreports - are activity report shown (yes = 1, no =0)
 * @property int $visible - 1: available to student, 0: not available
 * @property int $hiddensections - (deprecated, use courseformatoptions) How the hidden sections in the course are displayed to students
 * @property int $groupmode - no group, separate, visible
 * @property int $groupmodeforce - 1: yes, 0: no
 * @property int $defaultgroupingid - default grouping id
 * @property int $enablecompletion - Enabled, control via completion and activity settings
 * @property int $completionnotify - 1: yes 0: no
 * @property string $lang - forced course language
 * @property string $forcetheme - name of the force theme
 * @property CourseFormatOption[] $courseformatoptions - additional options for particular course format
 * @property CustomField[] $customfields - custom fields for the course
```

### UserRule
```php
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
```

