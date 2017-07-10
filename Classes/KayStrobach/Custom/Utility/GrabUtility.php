<?php

namespace KayStrobach\Custom\Utility;

use TYPO3\Flow\Annotations as Flow;

class GrabUtility {
	/**
	 * @var \TYPO3\Flow\Cache\Frontend\StringFrontend
	 * @Flow\Inject
	 */
	protected $cache;

	/**
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 * @Flow\Inject
	 */
	protected $logger;

	/**
	 * @var string
	 */
	protected $baseUri = NULL;

	/**
	 * @param string $uri
	 * @param string $selector
	 * @param bool $debug
	 * @param int $lifetime
	 *
	 * @return string
	 */
	public function getContent($uri, $selector, $debug = FALSE, $lifetime = 3600) {
		$cacheIdentifier = sha1($uri . $selector);
		$buffer = $this->cache->get($cacheIdentifier);

		if($buffer === FALSE) {
			libxml_use_internal_errors(TRUE);
			$domDocument = new \DOMDocument();
			$domDocument->loadHTMLFile($uri, LIBXML_NOERROR|LIBXML_NOWARNING);
			$this->detectBaseUri($domDocument, $uri);
			$this->fixRelativeUris($domDocument, 'a', 'href');
			$this->fixRelativeUris($domDocument, 'img', 'src');
			$node = $domDocument->getElementById($selector);
			$buffer = $domDocument->saveHTML($node);
			libxml_clear_errors();
			libxml_use_internal_errors(FALSE);
			$this->cache->set($cacheIdentifier, $buffer, array(sha1($uri)), $lifetime);
		}

		return $buffer;
	}

	/**
	 * @param \DOMDocument $domDocument
	 * @param string $uri
	 */
	protected function detectBaseUri(\DOMDocument $domDocument, $uri) {
		/** @var \DOMElement $baseTag */
		$baseTagList = $domDocument->getElementsByTagName('base');
		if($baseTagList->length > 0) {
			$baseTag = $baseTagList->item(0);
			$this->baseUri = $baseTag->getAttribute('href');
			return;
		}
		if(substr($uri, 0, 1) !== '/') {
			$this->baseUri = dirname($uri);
		}
	}

	/**
	 * @param \DOMDocument $domDocument
	 * @param string $tagName
	 * @param string $attributeName
	 */
	protected function fixRelativeUris(\DOMDocument $domDocument, $tagName = 'a', $attributeName = 'href') {
		$elements = $domDocument->getElementsByTagName($tagName);

		/** @var \DOMElement $element */
		foreach($elements as $element) {
			if (!$element->hasAttribute($attributeName)) {
				continue;
			}
			$uri = $element->getAttribute($attributeName);
			if (strpos(substr($uri, 0, 10), ':')) {
				continue;
			}
			if (substr($uri, 0, 1) !== '/') {
				$uri = $this->baseUri . $uri;
			} else {
			    if (substr($uri, 0, 1) !== '/') {
			        $uri = '/' . $uri;
                }
				$uri = parse_url($this->baseUri, PHP_URL_SCHEME) . '://' . parse_url($this->baseUri, PHP_URL_HOST) . $uri;
			}
			$element->setAttribute($attributeName, $uri);
		}
	}
}
