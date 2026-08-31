<?php

namespace tonimareta\moodle;

use yii\base\UnknownPropertyException;

class Model extends \yii\base\Model
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct();
        $this->configure($config);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UnknownPropertyException
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
     * @throws UnknownPropertyException
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
     * @param array $config
     * @return void
     */
    protected function configure(array $config): void
    {
        $attributes = $this->attributes();
        $relations = $this->relations();

        foreach ($attributes as $property) {
            $this->$property = $config[$property] ?? null;

            if (!empty($relations[$property])) {
                if (!empty($config[$property])) {
                    if (!is_array($config[$property])) {
                        $this->$property = new $relations[$property]($config[$property]);
                        continue;
                    }

                    if (empty($config[$property][0]) || str_ends_with($property, 'options')) {
                        $this->$property = new $relations[$property]($config[$property]);
                        continue;
                    }

                    $items = [];
                    foreach ($config[$property] as $item) {
                        $items[] = new $relations[$property]($item);
                    }

                    $this->$property = $items;
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function relations(): array
    {
        return [];
    }
}