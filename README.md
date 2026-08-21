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

## Table of Contents
1. [REST Models](#rest-models)
   - [Course](#course)
   - [Course Module](#course-module)
2. [Responses](#responses)
   - [Models](#models)
   - [Objects](#objects)
   - [Options](#options)

# REST Models
## Course

```php
use tonimareta\moodle\models\Course;
```

### Get course by id
```php
Course::getById(int $id): Course;
```

### Get courses by ids
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByIds(array $ids): Course[];
```

### Get course by shortname
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByShortname(string $shortname): Course;
```

### Get course by idnumber
```php
// Moodle web service function: core_course_get_courses_by_field
Course::getByIdNumber(int $idNumber): Course;
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

### Get course contents (sections with modules)
```php
// Moodle web service function: core_course_get_contents
$course = Course::getById($id);
$course->getContent(?CourseContentOption $options = null): CourseSection[]
```

## Course Module

```php
use tonimareta\moodle\models\CourseModule;
```

### Get course module by id
```php
CourseModule::getById(int $id): CourseModule;
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

## Objects
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

## Options
### CourseContentOption
```php
 * @property bool $excludemodules
 * @property bool $excludecontents
 * @property bool $includestealthmodules
 * @property int $sectionid
 * @property int $sectionnumber
 * @property int $cmid
 * @property string $modname
 * @property int $modid
```

### CourseFormatOption
```php
 * @property int $hiddensections
 * @property int $coursedisplay
 * @property int $indentation
```