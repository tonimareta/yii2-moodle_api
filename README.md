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
2. [Responses](#responses)
   - [Models](#models)
   - [Objects](#objects)
   - [Options](#options)
   - [Rules](#rules)

# REST Models
## Course
```php
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
$course->getCategory(): CourseCategory|null;
```

### Get course contents (sections with modules)
```php
// Moodle web service function: core_course_get_contents
$course = Course::getById($id);
$course->getContent(CourseContentOption|null $options = null): CourseSection[];
```

### Create course
```php
// Moodle web service function: core_course_create_courses
// CourseCreateRule used for filter params
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
// CourseUpdateRule used for filter params
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
CourseCategory::getByField(CategoryCriteriaOption $criteria, bool $addSubCategories = true): CourseCategory[];
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
// CategoryCreateRule used for filter params
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
// CategoryUpdateRule used for filter params
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

## Course Section
```php
use tonimareta\moodle\models\CourseSection;
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
### CategoryCriteriaOption
```php
 * @property int $id - the category id
 * @property string $ids - category ids separated by commas
 * @property string $name - the category name
 * @property int $parent - the parent category id
 * @property string $idnumber - category idnumber - user must have 'moodle/category:manage' to search on idnumber
 * @property int $visible - whether the returned categories must be visible or hidden
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

## Rules
### CategoryCreateRule
```php
 * @property string $name - category name
 * @property string $idnumber - category id number
 * @property int $parent - parent category id
 * @property string $description - category description
 * @property int $descriptionformat  - description format (1 = HTML, 0 = MOODLE, 2 = PLAIN, or 4 = MARKDOWN)
```

### CategoryUpdateRule
```php
 * @property int $id
 * CategoryCreateRule::attributes()
```

### CourseCreateRule
```php
 * @property string $fullname
 * @property string $shortname
 * @property int $categoryid
 * @property string $idnumber
 * @property string $summary
 * @property int $summaryformat
 * @property string $format
 * @property int $showgrades
 * @property int $newsitems
 * @property int $startdate
 * @property int $enddate
 * @property int $numsections
 * @property int $maxbytes
 * @property int $showreports
 * @property int $visible
 * @property int $hiddensections
 * @property int $groupmode
 * @property int $groupmodeforce
 * @property int $defaultgroupingid
 * @property int $enablecompletion
 * @property int $completionnotify
 * @property string $lang
```

### CourseUpdateRule
```php
 * @property int $id
 * CourseCreateRule::attributes()
```

