<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 04.05.14
 * Time: 13:25
 */

namespace KayStrobach\Custom\ViewHelpers;

use Neos\Flow\Annotations as Flow;

class SurfContextViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractConditionViewHelper{
	/**
	 * @Flow\Inject
	 * @var \Neos\Flow\Core\Bootstrap
	 */
	protected $bootstrap;

	/**
	 * Condition for Context
	 *
	 * @param string $context
	 * @return string
	 */
	public function render($context = 'Development') {
		$contextObject = $this->bootstrap->getContext();

		if( (((string)$contextObject) === $context)) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}