<?php

namespace tonimareta\moodle;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

class RestModel extends Model
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $attributes = array_merge_recursive(
            array_fill_keys($this->attributes(), null),
            array_fill_keys($this->extraFields(), null),
        );

        if (!array_key_exists($name, $attributes)) {
            return parent::__get($name);
        }

        return $attributes[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (!in_array($name, $this->attributes()) && !in_array($name, $this->extraFields())) {
            parent::__set($name, $value);
        } else {
            $this->$name = $value;
        }
    }

    /**
     * @param string $function
     * @param array $params
     * @param string $method
     * @return mixed
     */
    public static function connect(string $function, array $params = [], string $method = 'post'): mixed
    {
        return static::db()
            ->setFunction($function)
            ->setMethod($method)
            ->setParams($params)
            ->send();
    }

    /**
     * @param array $data
     * @return static
     */
    public static function loadData(array $data): static
    {
        $model = new static($data);
        $attributes = $model->attributes();

        foreach ($attributes as $property) {
            if (empty($model->$property)) {
                $model->$property = null;
            }
        }

        return $model;
    }

    /**
     * @param array $dataset
     * @return static[]
     */
    public static function loadDataMultiple(array $dataset): array
    {
        return array_map(fn ($data) => static::loadData($data), $dataset);
    }

    /**
     * @return Connection
     * @throws InvalidConfigException
     */
    public static function db(): Connection
    {
        if (!Yii::$app->has('moodleAPI')) {
            throw new InvalidConfigException('The "moodleAPI" property must be set in web config in components section.');
        }

        return Yii::$app->get('moodleAPI');
    }
}