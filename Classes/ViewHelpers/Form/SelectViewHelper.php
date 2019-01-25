<?php

namespace KayStrobach\Custom\ViewHelpers\Form;

/**
 * This view helper generates a <select> dropdown list for the use with a form.
 * But in addition to the one in FLOW it allows to specify an empty value as top element
 */
class SelectViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Form\SelectViewHelper
{
    /**
     * Initialize arguments.
     *
     * @return void
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('nothingSelectedLabel', 'string', 'If specified an optionTag with value=NULL is prepended to the list.', false, null);
        $this->registerArgument('required', 'bool', 'decide wether required is set in field', false, false);
    }

    /**
     * Render the option tags.
     *
     * @param array $options the options for the form.
     * @return string rendered tags.
     */
    protected function renderOptionTags($options)
    {
        if ($this->arguments['nothingSelectedLabel'] !== null) {
            $this->arguments['prependOptionLabel'] = $this->arguments['nothingSelectedLabel'];
        }
        if ($this->arguments['required']) {
            $this->tag->addAttribute('required', 'required');
        }
        return parent::renderOptionTags($options);
    }
}
