<?php

namespace tonimareta\moodle;

class Option extends Model
{
    /**
     * @var string
     */
    public string $name = '';

    /**
     * @var mixed|null
     */
    public mixed $value = null;

    /**
     * @return array
     */
    public function getItems(): array
    {
        $options = [];
        $data = $this->toArray();

        foreach ($data as $name => $value) {
            $options[] = [
                'name' => $name,
                'value' => $value,
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