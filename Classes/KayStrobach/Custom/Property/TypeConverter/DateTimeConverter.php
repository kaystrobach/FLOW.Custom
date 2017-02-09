<?php

namespace KayStrobach\Custom\Property\TypeConverter;

use TYPO3\Flow\Property\Exception\TypeConverterException;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter;

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
            if (!(isset($source['dateFormat']) && $source['dateFormat'] !== '')) {
                return false;
            }
            if ($source['dateFormat'] === $source['date']) {
                return true;
            }
            return true;
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
