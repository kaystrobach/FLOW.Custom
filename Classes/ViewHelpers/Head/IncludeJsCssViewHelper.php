<?php

namespace KayStrobach\Custom\ViewHelpers\Head;


use Neos\Flow\ResourceManagement\Publishing\ResourcePublisher;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\Flow\Annotations as Flow;


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
     * @Flow\Inject
     * @var ResourcePublisher
     */
    protected $resourcePublisher;

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
        return '<link rel="stylesheet" href="' . htmlspecialchars($this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/KayStrobach.Custom/' . $path) . '?' . $timestamp . '">' . PHP_EOL;
    }

    public function getScriptTag($path) {
        $timestamp = sha1(file_get_contents('resource://KayStrobach.Custom/Public/' . $path));
        return '<script type="text/javascript" src="' . htmlspecialchars($this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/KayStrobach.Custom/' . $path) . '?' . $timestamp . '"></script>' . PHP_EOL;
    }

}
