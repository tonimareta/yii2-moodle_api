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
     * @param string|null $formId
     * @return static[]
     */
    public static function loadData(array $data, ?string $formId = null): array
    {
        if ($formId && !empty($data[$formId])) {
            $data = $data[$formId];
        }

        return array_map(fn ($data) => new static($data), $data);
    }
}