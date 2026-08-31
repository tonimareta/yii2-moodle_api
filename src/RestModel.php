<?php

namespace tonimareta\moodle;

use tonimareta\moodle\interfaces\CriteriaInterface;
use tonimareta\moodle\interfaces\OptionInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\MethodNotAllowedHttpException;

abstract class RestModel extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_DELETE = 'delete';
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

        $rules = static::crudRules();

        if (empty($rules[self::SCENARIO_DELETE])) {
            return false;
        }

        list($method, $params) = array_pad($rules[self::SCENARIO_DELETE], 2, null);

        if (!$method) {
            return false;
        }

        $result = static::connect($method, $params ?? []);

        if (!$result) {
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
     * @param CriteriaInterface $criteria
     * @return array
     * @throws Exception
     * @throws InvalidConfigException
     */
    abstract public static function getByCriteria(CriteriaInterface $criteria): array;

    /**
     * @param CriteriaInterface $criteria
     * @return static|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getByCriteriaOne(CriteriaInterface $criteria): ?static
    {
        $models = static::getByCriteria($criteria);
        return $models[0] ?? null;
    }

    /**
     * @param array $data
     * @param string|null $formId
     * @return static[]
     */
    public static function loadData(array $data, ?string $formId = null): array
    {
        if ($formId && isset($data[$formId])) {
            $data = $data[$formId];
        }

        return array_map(fn ($data) => new static($data), $data);
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_CREATE => [],
            self::SCENARIO_DELETE => [],
            self::SCENARIO_UPDATE => [],
        ]);
    }

    /**
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @throws MethodNotAllowedHttpException
     */
    public function save(): bool
    {
        if (!$pk = static::primaryKey()) {
            return false;
        }

        $scenario = !empty($this->{$pk}) ? self::SCENARIO_UPDATE : self::SCENARIO_CREATE;
        $this->setScenario($scenario);

        if (!$this->validate()) {
            return false;
        }

        $rules = static::crudRules();

        if (empty($rules[$scenario])) {
            throw new MethodNotAllowedHttpException('Method not allowed.');
        }

        list($method, $params) = array_pad($rules[$scenario], 2, null);

        if (!$method) {
            throw new MethodNotAllowedHttpException("The {$method} not allowed.");
        }

        $models = static::connect($method, $params ?? []);

        if (!is_array($models)) {
            return !empty($models);
        }

        if (empty($models[0])) {
            return false;
        }

        $this->setAttributes($models[0]);
        $this->load($models[0]);

        return true;
    }

    /**
     * @return false[]
     */
    protected function crudRules(): array
    {
        return [
            self::SCENARIO_CREATE => [],
            self::SCENARIO_DELETE => [],
            self::SCENARIO_UPDATE => [],
        ];
    }

    /**
     * @return string
     */
    protected static function primaryKey(): string
    {
        return 'id';
    }
}