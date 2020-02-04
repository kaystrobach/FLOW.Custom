<?php

namespace KayStrobach\Custom\ViewHelpers\Form;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Formatter\NumberFormatter;
use Neos\FluidAdaptor\ViewHelpers\Form\TextfieldViewHelper;


class NumberTextfieldViewHelper extends TextfieldViewHelper
{
    /**
     * @Flow\Inject
     * @var NumberFormatter
     */
    protected $numberFormatter;

    /**
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('decimals', 'int', '', false, 2);
        $this->registerArgument('decimalSeparator', 'string', '', false, '.');
        $this->registerArgument('thousandsSeparator', 'string', '', false, ',');
    }

    public function render()
    {
        if ($this->arguments['thousandsSeparator'] === $this->arguments['decimalSeparator']) {
            $this->arguments['thousandsSeparator'] = '';
        }

        $name = $this->getName();
        $this->registerFieldNameForFormTokenGeneration($name);

        $this->tag->addAttribute('name', $name . '[value]');
        $this->tag->addAttribute('type', $this->arguments['type']);

        $value = $this->getValueAttribute();
        if ($value !== null) {
            if (is_array($value) && isset($value['value'])) {
                $this->tag->addAttribute('value', $value['value']);
            } elseif ($value) {
                $this->tag->addAttribute('value', $value);
            }
        }

        if ($this->arguments['required'] === true) {
            $this->tag->addAttribute('required', 'required');
        }
        $this->addAdditionalIdentityPropertiesIfNeeded();
        $this->setErrorClassAttribute();

        $buffer = $this->tag->render();
        $buffer .= '<input type="hidden" value="' . htmlspecialchars($this->arguments['decimalSeparator'], ENT_QUOTES | ENT_HTML5) . '" name="' . $this->getName() . '[decimalSeparator]" />';
        $buffer .= '<input type="hidden" value="' . htmlspecialchars($this->arguments['thousandsSeparator'], ENT_QUOTES | ENT_HTML5) . '" name="' . $this->getName() . '[thousandsSeparator]" />';
        return $buffer;
    }

    /**
     * Returns the current value of this Form ViewHelper and converts it to an identifier string in case it's an object
     * The value is determined as follows:
     * * If property mapping errors occurred and the form is re-displayed, the *last submitted* value is returned
     * * Else the bound property is returned (only in objectAccessor-mode)
     * * As fallback the "value" argument of this ViewHelper is used
     *
     * @param boolean $ignoreSubmittedFormData By default the submitted form value has precedence over value/property argument upon re-display. With this flag set the submitted data is not evaluated (e.g. for checkbox and hidden fields where the value attribute should not be changed)
     * @return mixed Value
     */
    protected function getValueAttribute($ignoreSubmittedFormData = false)
    {
        $value = parent::getValueAttribute($ignoreSubmittedFormData);
        if (is_numeric($value)) {
            $value = number_format(
                (float)$value,
                $this->arguments['decimals'],
                $this->arguments['decimalSeparator'],
                $this->arguments['thousandsSeparator']
            );
        }
        return $value;
    }
}
