<?php

namespace tonimareta\moodle;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\MethodNotAllowedHttpException;

class RestModel extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_FIND = 'find';
    const SCENARIO_UPDATE = 'update';

    /**
     * @param string $function
     * @param array $params
     * @param string $method
     * @return mixed
     * @throws Exception
     * @throws InvalidConfigException
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
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function delete(): bool
    {
        $this->setScenario(self::SCENARIO_DELETE);

        if (!$this->validate()) {
            return false;
        }

        if (!$pk = $this->primaryKey()) {
            return false;
        }

        if (empty($this->{$pk})) {
            return false;
        }

        $result = static::execute(self::SCENARIO_DELETE, [$this->{$pk}]);

        if (!is_array($result) && empty($result)) {
            return false;
        }

        foreach ($this as $property => $value) {
            $this->{$property} = null;
        }

        return true;
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
     * @param array $conditions
     * @return static[]
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function findAll(array $conditions): array
    {
        $services = static::services();
        $formName = static::modelName(true);
        $method = $services[self::SCENARIO_FIND][0] ?? null;

        if (!$method) {
            return [];
        }

        $models = static::connect($method, $conditions);

        if (!is_array($models)) {
            return [];
        }

        $data = $models[$formName] ?? $models;

        if (empty($data)) {
            return [];
        }

        return static::loadData($data);
    }

    /**
     * @param array $condition
     * @return static|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function findOne(array $condition): ?static
    {
        $models = static::findAll($condition);
        return $models[0] ?? null;
    }

    /**
     * @param array $data
     * @param string|null $formName
     * @return static[]
     */
    public static function loadData(array $data, ?string $formName = null): array
    {
        if ($formName && isset($data[$formName])) {
            $data = $data[$formName];
        }

        return array_map(fn($model) => new static($model), $data);
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function save(): bool
    {
        if (!$pk = $this->primaryKey()) {
            return false;
        }

        $scenario = !empty($this->{$pk}) ? self::SCENARIO_UPDATE : self::SCENARIO_CREATE;
        $this->setScenario($scenario);

        if (!$this->validate()) {
            return false;
        }

        $fields = $this->scenarios()[$scenario] ?? [];
        $conditions = $this->toArray($fields);
        $models = static::execute($scenario, [$conditions]);

        if (!is_array($models)) {
            return !empty($models);
        }

        if (empty($models)) {
            return true;
        }

        if (empty($models[0])) {
            return false;
        }

        $this->setAttributes($models[0], false);
        return true;
    }

    /**
     * @return array
     */
    public function saveAttributes(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $saveAttributes = $this->saveAttributes();
        $pk = $this->primaryKey();

        $scenarios[self::SCENARIO_CREATE] = $saveAttributes;
        $scenarios[self::SCENARIO_DELETE] = [$pk];
        $scenarios[self::SCENARIO_FIND] = [];
        $scenarios[self::SCENARIO_UPDATE] = ArrayHelper::merge([$pk], $saveAttributes);

        return $scenarios;
    }

    /**
     * @return array
     */
    public static function services(): array
    {
        return [];
    }

    /**
     * @param string $scenario
     * @param array $conditions
     * @return mixed
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    protected static function execute(string $scenario, array $conditions = []): mixed
    {
        $services = static::services();
        list($method, $formName) = array_pad($services[$scenario] ?? [], 2, null);
        $formName = $formName ?? static::modelName(true);

        if (!$method) {
            throw new MethodNotAllowedHttpException("The {$scenario} method not allowed.");
        }

        if (empty($conditions)) {
            return false;
        }

        return static::connect($method, [$formName => array_filter($conditions)]);
    }
}