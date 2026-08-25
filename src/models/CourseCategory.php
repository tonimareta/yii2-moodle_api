<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\options\CategoryCriteriaOption;
use tonimareta\moodle\RestModel;
use tonimareta\moodle\rules\CategoryCreateRule;
use tonimareta\moodle\rules\CategoryUpdateRule;
use yii\base\InvalidConfigException;
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
     * @param CategoryCriteriaOption $criteria
     * @param bool $addSubCategories
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByField(CategoryCriteriaOption $criteria, bool $addSubCategories = true): array
    {
        $categories = static::connect('core_course_get_categories', [
            'criteria' => $criteria->getItems(),
            'addsubcategories' => (int) $addSubCategories,
        ]);

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
        $category = static::getByField(new CategoryCriteriaOption(['id' => $id]));
        return $category[0] ?? null;
    }

    /**
     * @param string $idnumber
     * @return CourseCategory|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIdnumber(string $idnumber): ?static
    {
        $category = static::getByField(new CategoryCriteriaOption(['idnumber' => $idnumber]));
        return $category[0] ?? null;
    }

    /**
     * @param array $ids
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIds(array $ids): array
    {
        return static::getByField(new CategoryCriteriaOption(['ids' =>  implode(',', $ids)]));
    }

    /**
     * @param string $name
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByName(string $name): array
    {
        return static::getByField(new CategoryCriteriaOption(['name' =>  trim($name)]));
    }

    /**
     * @param int $parentId
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByParentId(int $parentId): array
    {
        return static::getByField(new CategoryCriteriaOption(['parent' =>  $parentId]));
    }

    /**
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getHidden(): array
    {
        return static::getByField(new CategoryCriteriaOption(['visible' =>  0]));
    }

    /**
     * @return CourseCategory[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getVisible(): array
    {
        return static::getByField(new CategoryCriteriaOption(['visible' =>  1]));
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
        $params = $this->toArray();

        return [
            self::SCENARIO_CREATE => ['core_course_create_categories', ['categories' => [new CategoryCreateRule($params)]]],
            self::SCENARIO_DELETE => ['core_course_delete_categories', ['categories' => [['id' => $this->id]]]],
            self::SCENARIO_UPDATE => ['core_course_update_categories', ['categories' => [new CategoryUpdateRule($params)]]],
        ];
    }
}