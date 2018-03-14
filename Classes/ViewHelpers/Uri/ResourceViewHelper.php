<?php
namespace KayStrobach\Custom\ViewHelpers\Uri;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.NeosTypo3Org".    *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\FluidAdaptor\Core\ViewHelper\Exception\InvalidVariableException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * A extended resource view helper with cache busting
 */
class ResourceViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Uri\ResourceViewHelper {

    /**
     * Initialize and register all arguments.
     *
     * @return void
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('addVersion', 'bool', 'Whether resource should get a version string appended.', false, true);
    }

	/**
	 * Renders a URI to the resource and adds a version parameter to the URI if $addVersion is set
	 *
	 * @param string $path The location of the resource, can be either a path relative to the Public resource directory of the package or a resource://... URI
	 * @param string $package Target package key. If not set, the current package key will be used
	 * @param Resource|\Neos\Flow\ResourceManagement\PersistentResource $resource If specified, this resource object is used instead of the path and package information
	 * @param boolean $localize Whether resource localization should be attempted or not
	 * @param boolean $addVersion Add a version number as a cache buster to the generated URI to enable flushing of resources with infinite expiration dates
	 * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception\InvalidVariableException
	 * @return string The absolute URI to the resource
	 */
	public function render()
    {
        return self::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
	}

	/**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     * @throws InvalidVariableException
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $uri = parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
        if (($arguments['addVersion'] === true) && ($arguments['resource'] === null)) {
            $package = $arguments['package'];
            if ($package === NULL) {
                $controllerContext = $renderingContext->getControllerContext();
                $package = $controllerContext->getRequest()->getControllerPackageKey();
            }
            $resourceUri = 'resource://' . $package . '/Public/' . $arguments['path'];
            try {
                $resourceStats = stat($resourceUri);
            } catch (\Exception $e) {
                $resourceStats = FALSE;
            }
            if ($resourceStats !== FALSE) {
                $mtime = $resourceStats['mtime'];
                $uri = $uri . '?' . $mtime;
            }
        }
        return $uri;
    }
}
