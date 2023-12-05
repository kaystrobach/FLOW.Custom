<?php

namespace KayStrobach\Custom\ViewHelpers\Form;

use KayStrobach\Custom\Traits\SelectPrePopulateTrait;
use Neos\FluidAdaptor\ViewHelpers\Form\SelectViewHelper as OriginalSelectViewHelper;

/**
 * This view helper generates a <select> dropdown list for the use with a form.
 * But in addition to the one in FLOW it allows to specify an empty value as top element
 */
class SelectViewHelper extends OriginalSelectViewHelper
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
        if (!array_key_exists('required', $this->argumentDefinitions)) {
            $this->registerArgument('required', 'boolean', '', false, false);
        }
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

    use SelectPrePopulateTrait;

    protected function getOptions()
    {
        $this->prepopulateOptions();
        return parent::getOptions();
    }
}
