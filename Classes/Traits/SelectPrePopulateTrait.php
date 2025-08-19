<?php

namespace KayStrobach\Custom\Traits;

trait SelectPrePopulateTrait
{
    protected function prepopulateOptions()
    {
        if (!is_array($this->arguments['options']) && !($this->arguments['options'] instanceof \Traversable)) {
            $this->arguments['options'] = [];
        }

        $values = $this->getPropertyValue();
        if ($this->arguments['multiple']) {
            foreach ($values as $value) {
                if (!in_array($value, (array)$this->arguments['options'], true)) {
                    $this->arguments['options'][] = $value;
                }
            }
            return parent::getOptions();
        }

        if (is_array($this->arguments['options'])) {
            if (!array_key_exists($values, $this->arguments['options'])) {
                $this->arguments['options'][] = $values;
            }
        }


        return parent::getOptions();
    }
}
