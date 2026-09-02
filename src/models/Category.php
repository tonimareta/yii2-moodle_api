<?php

namespace tonimareta\moodle\models;

use tonimareta\moodle\RestModel;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\MethodNotAllowedHttpException;

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
class Category extends RestModel
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
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function delete(): bool
    {
        $this->setScenario(self::SCENARIO_DELETE);

        if (!$this->id || !$this->validate()) {
            return false;
        }

        $result = static::execute(self::SCENARIO_DELETE, [['id' => $this->id]]);

        if (!is_array($result) && empty($result)) {
            return false;
        }

        foreach ($this as $property => $value) {
            $this->{$property} = null;
        }

        return true;
    }

    /**
     * @param array $conditions
     * @return static[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function findAll(array $conditions): array
    {
        $criteria = [];

        foreach ($conditions as $key => $value) {
            if ($key == 'addsubcategories') {
                continue;
            }

            $criteria[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return parent::findAll([
            'criteria' => $criteria,
            'addsubcategories' => $conditions['addsubcategories'] ?? 0,
        ]);
    }

    /**
     * @param int $id
     * @return Category|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getById(int $id): ?static
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param string $idnumber
     * @return Category|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIdnumber(string $idnumber): ?static
    {
        return static::findOne(['idnumber' => $idnumber]);
    }

    /**
     * @param array $ids
     * @return Category[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByIds(array $ids): array
    {
        return static::findAll(['ids' =>  implode(',', $ids)]);
    }

    /**
     * @param string $name
     * @return Category[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByName(string $name): array
    {
        return static::findAll(['name' =>  trim($name)]);
    }

    /**
     * @param int $parentId
     * @return Category[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByParentId(int $parentId): array
    {
        return static::findAll(['parent' =>  $parentId]);
    }

    /**
     * @param bool $visible
     * @param int|null $parent
     * @return mixed
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByVisibility(bool $visible = true, ?int $parent = null): array
    {
        return static::findAll(array_filter(['parent' => $parent, 'visible' =>  (int) $visible], 'strlen'));
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
            [['name', 'idnumber', 'description', 'path', 'theme'], 'string'],
        ];
    }

    /**
     * @return string[]
     */
    public function saveAttributes(): array
    {
        return [
            'id',
            'name',
            'idnumber',
            'parent',
            'description',
            'descriptionformat',
        ];
    }

    /**
     * @return array[]
     */
    public static function services(): array
    {
        return [
            self::SCENARIO_CREATE => ['core_course_create_categories'],
            self::SCENARIO_DELETE => ['core_course_delete_categories'],
            self::SCENARIO_FIND => ['core_course_get_categories'],
            self::SCENARIO_UPDATE => ['core_course_update_categories'],
        ];
    }
}