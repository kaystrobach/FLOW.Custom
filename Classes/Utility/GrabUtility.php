<?php

namespace KayStrobach\Custom\Utility;

use DOMDocument;
use DOMElement;
use Neos\Cache\Exception\InvalidDataException;
use Neos\Cache\Frontend\StringFrontend;
use Neos\Flow\Annotations as Flow;
use Psr\Log\LoggerInterface;

class GrabUtility
{
    /**
     * @var StringFrontend
     * @Flow\Inject
     */
    protected $cache;

    /**
     * @var string
     */
    protected $baseUri = null;

    /**
     * @param string $uri
     * @param string $selector
     * @param bool $debug
     * @param int $lifetime
     *
     * @return string
     * @throws InvalidDataException
     */
    public function getContent($uri, $selector, $debug = false, $lifetime = 3600)
    {
        $cacheIdentifier = sha1($uri . $selector);
        $buffer = $this->cache->get($cacheIdentifier);

        if ($buffer === false) {
            libxml_use_internal_errors(true);
            $domDocument = new DOMDocument();
            $domDocument->loadHTMLFile($uri, LIBXML_NOERROR | LIBXML_NOWARNING);
            $this->detectBaseUri($domDocument, $uri);
            $this->fixRelativeUris($domDocument, 'a', 'href');
            $this->fixRelativeUris($domDocument, 'img', 'src');
            $node = $domDocument->getElementById($selector);
            $buffer = $domDocument->saveHTML($node);
            libxml_clear_errors();
            libxml_use_internal_errors(false);
            $this->cache->set($cacheIdentifier, $buffer, array(sha1($uri)), $lifetime);
        }

        return $buffer;
    }

    /**
     * @param DOMDocument $domDocument
     * @param string $uri
     */
    protected function detectBaseUri(DOMDocument $domDocument, $uri)
    {
        /** @var DOMElement $baseTag */
        $baseTagList = $domDocument->getElementsByTagName('base');
        if ($baseTagList->length > 0) {
            $baseTag = $baseTagList->item(0);
            $this->baseUri = $baseTag->getAttribute('href');
            return;
        }
        if (substr($uri, 0, 1) !== '/') {
            $this->baseUri = dirname($uri);
        }
    }

    /**
     * @param DOMDocument $domDocument
     * @param string $tagName
     * @param string $attributeName
     */
    protected function fixRelativeUris(DOMDocument $domDocument, $tagName = 'a', $attributeName = 'href')
    {
        $elements = $domDocument->getElementsByTagName($tagName);

        /** @var DOMElement $element */
        foreach ($elements as $element) {
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
                $uri = parse_url($this->baseUri, PHP_URL_SCHEME) . '://' . parse_url($this->baseUri,
                        PHP_URL_HOST) . $uri;
            }
            $element->setAttribute($attributeName, $uri);
        }
    }
}
