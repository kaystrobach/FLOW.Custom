<?php

namespace KayStrobach\Custom\ViewHelpers\Form;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Formatter\NumberFormatter;
use Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper;
use Neos\FluidAdaptor\ViewHelpers\Form\TextfieldViewHelper;


class NumberTextfieldViewHelper extends AbstractFormFieldViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'input';

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

        $this->registerArgument('required', 'boolean', '', false, false);
        $this->registerArgument('type', 'string', '', false, 'text');

        $this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', false, 'f3-form-error');
        $this->registerArgument('decimals', 'int', '', false, 2);
        $this->registerArgument('decimalSeparator', 'string', '', false, '.');
        $this->registerArgument('thousandsSeparator', 'string', '', false, ',');

        $this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
        $this->registerTagAttribute('maxlength', 'int', 'The maxlength attribute of the input field (will not be validated)');
        $this->registerTagAttribute('readonly', 'string', 'The readonly attribute of the input field');
        $this->registerTagAttribute('size', 'int', 'The size of the input field');
        $this->registerTagAttribute('placeholder', 'string', 'The placeholder of the input field');
        $this->registerTagAttribute('autofocus', 'string', 'Specifies that a input field should automatically get focus when the page loads');


        $this->registerUniversalTagAttributes();
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
