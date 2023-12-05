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
        if (!in_array($values, (array)$this->arguments['options'], true)) {
            $this->arguments['options'][] = $values;
        }

        return parent::getOptions();
    }
}
