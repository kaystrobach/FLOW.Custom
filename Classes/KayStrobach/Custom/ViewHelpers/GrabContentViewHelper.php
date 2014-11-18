<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 18.11.14
 * Time: 09:55
 */

namespace KayStrobach\Custom\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

class GrabContentViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @var \TYPO3\Flow\Cache\Frontend\StringFrontend
	 * @Flow\Inject
	 */
	protected $cache;

	/**
	 * @param string $uri
	 * @param string $selector
	 * @param bool $debug
	 * @return string
	 */
	public function render($uri, $selector, $debug = FALSE, $lifetime = 60) {
		$cacheIdentifier = sha1($uri . $selector);
		$buffer = $this->cache->get($cacheIdentifier);

		if($buffer === FALSE) {
			libxml_use_internal_errors(TRUE);
			$domDocument = new \DOMDocument();
			$domDocument->loadHTMLFile($uri, LIBXML_NOERROR|LIBXML_NOWARNING);
			$node = $domDocument->getElementById($selector);
			$buffer = $domDocument->saveHTML($node);
			libxml_clear_errors();
			libxml_use_internal_errors(FALSE);
			$this->cache->set($cacheIdentifier, $buffer, array(sha1($uri)), $lifetime);
		}

		return $buffer;
	}
} 