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