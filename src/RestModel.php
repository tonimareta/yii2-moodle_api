<?php

namespace tonimareta\moodle;

use Yii;
use yii\base\InvalidConfigException;

class RestModel extends Model
{
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

    /**
     * @param array $data
     * @return static
     */
    public static function loadData(array $data): static
    {
        return new static($data);
    }

    /**
     * @param array $dataset
     * @return static[]
     */
    public static function loadDataMultiple(array $dataset): array
    {
        return array_map(fn ($data) => static::loadData($data), $dataset);
    }
}