<?php
namespace KayStrobach\Custom\ViewHelpers\Form;

/*
 * This file is part of the KayStrobach.EventManager package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\FluidAdaptor\ViewHelpers\Form\ButtonViewHelper;

class DeleteButtonViewHelper extends ButtonViewHelper
{
    /**
     * Renders the button.
     *
     * @return string
     * @api
     */
    public function render()
    {
        $name = $this->getName();
        $this->registerFieldNameForFormTokenGeneration($name);

        $this->addAdditionalIdentityPropertiesIfNeeded();

        $this->tag->addAttribute('type', $this->arguments['type']);
        $this->tag->addAttribute('name', $name);
        $this->tag->addAttribute('value', $this->getValueAttribute(true));
        $this->tag->setContent($this->renderChildren());

        return $this->tag->render();
    }

    /**
     * Register a field name for inclusion in the HMAC / Form Token generation
     *
     * @param string $fieldName name of the field to register
     * @return void
     */
    protected function registerFieldNameForFormTokenGeneration($fieldName)
    {
        if ($this->viewHelperVariableContainer->exists(\Neos\FluidAdaptor\ViewHelpers\FormViewHelper::class, 'formFieldNames')) {
            $formFieldNames = $this->viewHelperVariableContainer->get(\Neos\FluidAdaptor\ViewHelpers\FormViewHelper::class, 'formFieldNames');
        } else {
            $formFieldNames = array();
        }
        $formFieldNames[] = $fieldName;

        if (!in_array($fieldName, $formFieldNames)) {
            parent::registerFieldNameForFormTokenGeneration($fieldName);
        }
    }
}
