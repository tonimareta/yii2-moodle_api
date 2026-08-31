<?php

namespace tonimareta\moodle;

use tonimareta\moodle\interfaces\OptionInterface;

class Option extends Model implements OptionInterface
{
    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return ['name', 'value'];
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $options = [];
        $data = $this->toArray();
        list($keyName, $keyValue) = static::keys();

        foreach ($data as $name => $value) {
            if (is_null($value)) {
                continue;
            }

            $options[] = [
                $keyName => $name,
                $keyValue => $value,
            ];
        }

        return $options;
    }

    /**
     * @param array $config
     * @return void
     */
    protected function configure(array $config): void
    {
        parent::configure($config);
        $attributes = $this->attributes();

        if (!empty($config[0])) {
            foreach ($config as $option) {
                if (!empty($option['name']) && in_array($option['name'], $attributes)) {
                    $this->{$option['name']} = $option['value'];
                }
            }
        }
    }
}