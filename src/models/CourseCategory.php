<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\rules\CategoryCreateRule;
use tonimareta\moodle\options\CategoryCriteriaOption;
use tonimareta\moodle\rules\CategoryRemoveRule;
use tonimareta\moodle\rules\CategoryUpdateRule;
use tonimareta\moodle\RestModel;
use yii\helpers\ArrayHelper;

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
     * @return CourseCategory|null
     */
    public function create(): ?CourseCategory
    {
        if (!$this->name) {
            return null;
        }

        $categories = static::createMany([array_filter($this->toArray())]);
        return $categories[0] ?? null;
    }

    /**
     * @param array $categories
     * @return array
     */
    public static function createMany(array $categories): array
    {
        $categories = array_filter(ArrayHelper::map($categories, 'name', fn($category) => (new CategoryCreateRule($category))->filterItems()));

        if (!$created = static::connect('core_course_create_categories', ['categories' => array_values($categories)])) {
            return [];
        }

        return array_values(array_filter(array_map(function ($category) use ($categories) {
            if (!empty($category['name']) && !empty($categories[$category['name']])) {
                $categories[$category['name']]['id'] = $category['id'];
                return new static($categories[$category['name']]);
            }

            return null;
        }, $created)));
    }

    /**
     * @param int|null $newParentId - the parent category to move the contents to, if specified
     * @param bool $recursive - recursively delete all contents inside this category or move contents to newParentId
     * @return bool
     */
    public function delete(?int $newParentId = null, bool $recursive = false): bool
    {
        if (!$this->id) {
            return false;
        }

        $remove = new CategoryRemoveRule(['id' => $this->id]);

        if (!is_null($newParentId)) {
            $remove->newparent = $newParentId;
        }

        if ($recursive) {
            $remove->newparent = null;
            $remove->recursive = (int) $recursive;
        }

        return static::deleteMany([$remove]);
    }

    /**
     * @param array $categoryRemoveRules
     * @return bool
     */
    public static function deleteMany(array $categoryRemoveRules): bool
    {
        $categories = array_map(fn ($category) => (new CategoryRemoveRule($category))->filterItems(), $categoryRemoveRules);
        return static::connect('core_course_delete_categories', ['categories' => array_values($categories)]);
    }

    /**
     * @param CategoryCriteriaOption $criteria
     * @param bool $addSubCategories
     * @return CourseCategory[]
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
     */
    public static function getById(int $id): ?CourseCategory
    {
        $category = static::getByField(new CategoryCriteriaOption(['id' => $id]));
        return $category[0] ?? null;
    }

    /**
     * @param string $idnumber
     * @return CourseCategory|null
     */
    public static function getByIdnumber(string $idnumber): ?CourseCategory
    {
        $category = static::getByField(new CategoryCriteriaOption(['idnumber' => $idnumber]));
        return $category[0] ?? null;
    }

    /**
     * @param array $ids
     * @return CourseCategory[]
     */
    public static function getByIds(array $ids): array
    {
        return static::getByField(new CategoryCriteriaOption(['ids' =>  implode(',', $ids)]));
    }

    /**
     * @param string $name
     * @return CourseCategory[]
     */
    public static function getByName(string $name): array
    {
        return static::getByField(new CategoryCriteriaOption(['name' =>  trim($name)]));
    }

    /**
     * @param int $parentId
     * @return CourseCategory[]
     */
    public static function getByParentId(int $parentId): array
    {
        return static::getByField(new CategoryCriteriaOption(['parent' =>  $parentId]));
    }

    /**
     * @return CourseCategory[]
     */
    public static function getHidden(): array
    {
        return static::getByField(new CategoryCriteriaOption(['visible' =>  0]));
    }

    /**
     * @return CourseCategory[]
     */
    public static function getVisible(): array
    {
        return static::getByField(new CategoryCriteriaOption(['visible' =>  1]));
    }

    /**
     * @return bool
     */
    public function update(): bool
    {
        if (!$this->id) {
            return false;
        }

        return static::updateMany([$this->toArray()]);
    }

    /**
     * @param array $categories
     * @return bool
     */
    public static function updateMany(array $categories): bool
    {
        $categories = array_filter(ArrayHelper::map($categories, 'id', fn($category) => (new CategoryUpdateRule($category))->filterItems()));
        return static::connect('core_course_update_categories', ['categories' => array_values($categories)]);
    }
}