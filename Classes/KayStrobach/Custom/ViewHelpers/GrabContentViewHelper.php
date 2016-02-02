<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 18.11.14
 * Time: 09:55
 */

namespace KayStrobach\Custom\ViewHelpers;

use KayStrobach\Custom\Utility\GrabUtility;
use TYPO3\Flow\Annotations as Flow;

class GrabContentViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var \KayStrobach\Custom\Utility\GrabUtility
	 */
	protected $grabUtility;
	
	/**
	 * Specifies whether the escaping interceptors should be disabled or enabled for the result of renderChildren() calls within this ViewHelper
	 * @see isChildrenEscapingEnabled()
	 *
	 * Note: If this is NULL the value of $this->escapingInterceptorEnabled is considered for backwards compatibility
	 *
	 * @var boolean
	 * @api
	 */
	protected $escapeChildren = false;

	/**
	 * @param string $uri
	 * @param string $selector
	 * @param bool $debug
	 * @param int $lifetime
	 * @return string
	 */
	public function render($uri, $selector, $debug = FALSE, $lifetime = 3600) {
		return $this->grabUtility->getContent($uri, $selector, $debug, $lifetime);
	}
} 
