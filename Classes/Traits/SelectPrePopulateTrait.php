<?php

namespace KayStrobach\Custom\Traits;

use Neos\Utility\ObjectAccess;

trait SelectPrePopulateTrait
{
    protected function prepopulateOptions()
    {
        $options = [];
        if (!is_array($this->arguments['options']) && !($this->arguments['options'] instanceof \Traversable)) {
            $this->arguments['options'] = [];
        }

        $values = $this->getPropertyValue();
        if ($this->arguments['multiple']) {
            foreach ($values as $value) {
                if (!in_array($value, (array)$this->arguments['options'], true)) {
                    // check wether we have an object or a scalar
                    $this->addOption($options, $value);
                }
            }
            return $options;
        }

        $this->addOption($options, $values);


        return $options;
    }

    protected function addOption(array &$options, mixed $value): void
    {
        if (is_null($value)) {
            $this->logger->debug('Add option: skipped because of null value');
            return;
        }
        if (is_object($value)) {
            $key = $this->persistenceManager->getIdentifierByObject($value);
            $label = ObjectAccess::getPropertyPath($value, (string)$this->arguments['optionLabelField']);
        } elseif(is_string($value)) {
            $key = $value;
            $label = $value;
        }

        if ($key && $label) {
            $this->logger->debug('Add option: ' . $key . ' => ' . $label);
            $options[$key] = $label;
        }
    }
}
