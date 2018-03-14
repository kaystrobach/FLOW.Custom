<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 03.03.15
 * Time: 13:28
 */

namespace KayStrobach\Custom\ViewHelpers\Arrays;


use Neos\FluidAdaptor\Core\ViewHelper\AbstractConditionViewHelper;

class InViewHelper extends AbstractConditionViewHelper {
	/**
	 * @param string $needle
	 * @param array $haystack
	 * @param bool $strict
	 * @return string
	 */
	public function render($needle, $haystack, $strict = FALSE) {
		if(is_array($haystack) && in_array($needle, $haystack, $strict)) {
			return $this->renderThenChild();
		}
        return $this->renderElseChild();
	}
}
