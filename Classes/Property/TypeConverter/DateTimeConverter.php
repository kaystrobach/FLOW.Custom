<?php

namespace KayStrobach\Custom\Property\TypeConverter;

use Neos\Flow\Property\Exception\TypeConverterException;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Property\TypeConverter\AbstractTypeConverter;

class DateTimeConverter extends AbstractTypeConverter
{
    /**
     * @var string
     */
    const CONFIGURATION_DATE_FORMAT = 'dateFormat';


    /**
     * @var array<string>
     */
    protected $sourceTypes = ['array'];

    /**
     * @var string
     */
    protected $targetType = 'DateTime';

    /**
     * @var integer
     */
    protected $priority = 2;

    /**
     * If conversion is possible.
     *
     * @param string $source
     * @param string $targetType
     * @return boolean
     */
    public function canConvertFrom($source, $targetType)
    {
        if (is_array($source)) {
            if ((isset($source['dateMask'])) && ($source['dateMask'] !== '') && (isset($source['date']))) {
                if ($source['dateMask'] === $source['date']) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Converts $source to a \DateTime using the configured dateFormat
     *
     * @param string|integer|array $source the string to be converted to a \DateTime object
     * @param string $targetType must be "DateTime"
     * @param array $convertedChildProperties not used currently
     * @param PropertyMappingConfigurationInterface $configuration
     * @return \DateTime
     * @throws TypeConverterException
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {

        return null;
    }
}
