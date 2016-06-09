<?php

namespace KayStrobach\Custom\ViewHelpers\Form;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Formatter\NumberFormatter;
use TYPO3\Fluid\ViewHelpers\Form\TextfieldViewHelper;


class NumberTextfieldViewHelper extends TextfieldViewHelper
{
    /**
     * @Flow\Inject
     * @var NumberFormatter
     */
    protected $numberFormatter;

    /**
     * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('decimals', 'int', '', false, 2);
        $this->registerArgument('decimalSeparator', 'int', '', false, '.');
        $this->registerArgument('thousandsSeparator', 'int', '', false, ',');
    }

    /**
     * Get the value of this form element.
     * Either returns arguments['value'], or the correct value for Object Access.
     *
     * @param boolean $convertObjects whether or not to convert objects to identifiers
     * @return mixed Value
     */
    protected function getValue($convertObjects = true)
    {
        $value = parent::getValue($convertObjects);
        $output = number_format(
            (float)$value,
            $this->arguments['decimals'],
            $this->arguments['decimalSeparator'],
            $this->arguments['thousandsSeparator']
        );
        return $output;
    }
}