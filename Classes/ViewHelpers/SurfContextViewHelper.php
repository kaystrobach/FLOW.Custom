<?php

namespace KayStrobach\Custom\ViewHelpers;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Utility\Environment;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class SurfContextViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractConditionViewHelper{
	public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('context', 'string', 'Context.', false);
    }

    /**
     * Static method which can be overridden by subclasses. If a subclass
     * requires a different (or faster) decision then this method is the one
     * to override and implement.
     *
     * Note: method signature does not type-hint that an array is desired,
     * and as such, *appears* to accept any input type. There is no type hint
     * here for legacy reasons - the signature is kept compatible with third
     * party packages which depending on PHP version would error out if this
     * signature was not compatible with that of existing and in-production
     * subclasses that will be using this base class in the future. Let this
     * be a warning if someone considers changing this method signature!
     *
     * @param array|NULL $arguments
     * @param RenderingContextInterface $renderingContext
     * @return boolean
     * @api
     */
    protected static function evaluateCondition($arguments = null, RenderingContextInterface $renderingContext)
    {
        $objectManager = Bootstrap::$staticObjectManager;
        $environment = $objectManager->get(Environment::class);
        $contextObject = $environment->getContext();
        $contextString = (string)$contextObject;
        return ($contextString === $arguments['context']);
    }
}
