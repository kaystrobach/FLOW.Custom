<?php

namespace KayStrobach\Custom\ViewHelpers\Head;

use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Flow\Annotations as Flow;


class IncludeJsCssViewHelper extends AbstractViewHelper
{
    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the render-result of this ViewHelper
     * @see isOutputEscapingEnabled()
     *
     * @var boolean
     * @api
     */
    protected $escapeOutput = false;

    /**
     * @Flow\Inject()
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @param bool|true $datetime
     * @return string
     */
    public function render($datetime = TRUE) {
        $buffer = '';
        if($datetime) {
            $buffer .= $this->getLinkTag('datetimepicker/jquery.datetimepicker.css');
            $buffer .= $this->getScriptTag('datetimepicker/build/jquery.datetimepicker.full.min.js');
            $buffer .= $this->getScriptTag('datetimepicker.js');
        }
        return $buffer;
    }

    public function getLinkTag($path) {
        $timestamp = sha1(file_get_contents('resource://KayStrobach.Custom/Public/' . $path));
        return '<link rel="stylesheet" href="' . htmlspecialchars($this->resourceManager->getPublicPackageResourceUriByPath('resource://KayStrobach.Custom/Public/' . $path)) . '?' . $timestamp . '">' . PHP_EOL;
    }

    public function getScriptTag($path) {
        $timestamp = sha1(file_get_contents('resource://KayStrobach.Custom/Public/' . $path));
        return '<script type="text/javascript" src="' . htmlspecialchars($this->resourceManager->getPublicPackageResourceUriByPath('resource://KayStrobach.Custom/Public/' . $path)) . '?' . $timestamp . '"></script>' . PHP_EOL;
    }

}
