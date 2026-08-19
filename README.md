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
1. [Moodle Course](#moodle-course)

## Moodle Course
```php
use tonimareta\moodle\models\MoodleCourse;
```

### Model Structure
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
 * @property array $summaryfiles
 * @property array $overviewfiles
 * @property string $showactivitydates
 * @property string $showcompletionconditions
 * @property array $contacts
 * @property array $enrollmentmethods
 * @property array $customfields
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
 * @property array $filters
 * @property array $courseformatoptions
```

### Get course by id
```php
MoodleCourse::getById(int $id): MoodleCourse;
```

### Get courses by ids
```php
MoodleCourse::getByIds(array $ids): MoodleCourse[];
```

### Get course by shortname
```php
MoodleCourse::getByShortname(string $shortname): MoodleCourse;
```

### Get course by idnumber
```php
MoodleCourse::getByIdNumber(int $idNumber): MoodleCourse;
```

### Get courses for category
```php
MoodleCourse::getByCategory(int $category): MoodleCourse[];
```

### Search courses by name
```php
MoodleCourse::searchByName(string $name): MoodleCourse[];
```