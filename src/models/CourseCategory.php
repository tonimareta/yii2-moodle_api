<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\criterias\CategoryCriteria;
use tonimareta\moodle\interfaces\CriteriaInterface;
use tonimareta\moodle\interfaces\OptionInterface;
use tonimareta\moodle\options\CategoryIncludeOption;
use tonimareta\moodle\RestModel;
use tonimareta\moodle\rules\CategoryRule;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;

/**
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
 */
class CourseCategory extends RestModel
{
    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'name',
            'idnumber',
            'description',
            'descriptionformat',
            'parent',
            'sortorder',
            'coursecount',
            'visible',
            'visibleold',
            'timemodified',
            'depth',
            'path',
            'theme',
        ];
    }

    /**
     * @param CategoryCriteria $criteria
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByCriteria(CriteriaInterface $criteria): array
    {
        $params = ['criteria' => $criteria->getItems()];

        if ($options = $criteria->options()) {
            $params = ArrayHelper::merge($params, $options);
        }

        $categories = static::connect('core_course_get_categories', $params);
        return static::loadData($categories);
    }

    /**
     * @param int $id
     * @return CourseCategory|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getById(int $id): ?static
    {
        return static::getByCriteriaOne(new CategoryCriteria(['id' => $id]));
    }

    /**
     * @param string $idnumber
     * @return CourseCategory|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIdnumber(string $idnumber): ?static
    {
        return static::getByCriteriaOne(new CategoryCriteria(['idnumber' => $idnumber]));
    }

    /**
     * @param array $ids
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIds(array $ids): array
    {
        return static::getByCriteria(new CategoryCriteria(['ids' =>  implode(',', $ids)]));
    }

    /**
     * @param string $name
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByName(string $name): array
    {
        return static::getByCriteria(new CategoryCriteria(['name' =>  trim($name)]));
    }

    /**
     * @param int $parentId
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByParentId(int $parentId): array
    {
        return static::getByCriteria(new CategoryCriteria(['parent' =>  $parentId]));
    }

    /**
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getHidden(): array
    {
        return static::getByCriteria(new CategoryCriteria(['visible' =>  0]));
    }

    /**
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getVisible(): array
    {
        return static::getByCriteria(new CategoryCriteria(['visible' =>  1]));
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],
            [['id'], 'required', 'on' => self::SCENARIO_UPDATE],

            [[
                'id', 'descriptionformat', 'parent', 'sortorder', 'coursecount', 'visible', 'visibleold',
                'timemodified', 'depth',
            ], 'integer'],
            [['name','idnumber','description','path','theme'], 'string'],
        ];
    }

    /**
     * @return array[]
     */
    protected function crudRules(): array
    {
        $rule = new CategoryRule($this->toArray());
        $params = $rule->filterItems();

        return [
            self::SCENARIO_CREATE => ['core_course_create_categories', ['categories' => [$params]]],
            self::SCENARIO_DELETE => ['core_course_delete_categories', ['categories' => [['id' => $this->id]]]],
            self::SCENARIO_UPDATE => ['core_course_update_categories', ['categories' => [$params]]],
        ];
    }
}