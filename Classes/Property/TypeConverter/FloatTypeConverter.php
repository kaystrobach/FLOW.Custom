<?php

namespace KayStrobach\Custom\Property\TypeConverter;
use Neos\Error\Messages\Error;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\TypeConverter\AbstractTypeConverter;

/**
 * @Flow\Scope("singleton")
 * Class FloatConverter
 * @package KayStrobach\Custom\Property\TypeConverter
 */
class FloatTypeConverter extends AbstractTypeConverter
{
    /**
     * @var array<string>
     */
    protected $sourceTypes = ['array'];

    /**
     * @var string
     */
    protected $targetType = 'float';

    /**
     * @var integer
     */
    protected $priority = 1;

    /**
     * Actually convert from $source to $targetType, by doing a typecast.
     *
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     * @return float|\Neos\Error\Messages\Error
     * @api
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        if (!is_array($source)) {
            return new Error('"%s" cannot be converted to a float value.', 1332934124, [print_r($source, true)]);
        }

        if (!isset($source['value'])) {
            return new Error('"%s" value field not set.', 1332934125, [print_r($source, true)]);
        }

        if (!isset($source['decimalSeparator'])) {
            return new Error('"%s" decimalSeparator field not set.', 1332934126, [print_r($source, true)]);
        }

        if (!isset($source['thousandsSeparator'])) {
            return new Error('"%s" decimalSeparator field not set.', 1332934127, [print_r($source, true)]);
        }

        return $this->convert(
            $source['value'],
            $source['thousandsSeparator'],
            $source['decimalSeparator']
        );
    }

    protected function convert($value, $thousandsSeparator, $decimalSeparator) {
        $value = str_replace(
            [
                $thousandsSeparator,
                $decimalSeparator
            ],
            [
                '',
                '.'
            ],
            $value
        );
        return (float)$value;
    }
}