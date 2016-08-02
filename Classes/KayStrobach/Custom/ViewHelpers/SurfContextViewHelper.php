<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 04.05.14
 * Time: 13:25
 */

namespace KayStrobach\Custom\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

class SurfContextViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractConditionViewHelper{
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Core\Bootstrap
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