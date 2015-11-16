<?php

namespace KayStrobach\Custom\ViewHelpers\Head;


use TYPO3\Flow\Resource\Publishing\ResourcePublisher;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Flow\Annotations as Flow;


class IncludeJsCssViewHelper extends AbstractViewHelper
{
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
        return '<link rel="stylesheet" href="' . htmlspecialchars($this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/KayStrobach.Custom/' . $path) . '">' . PHP_EOL;
    }

    public function getScriptTag($path) {
        return '<script type="text/javascript" src="' . htmlspecialchars($this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/KayStrobach.Custom/' . $path) . '"></script>' . PHP_EOL;
    }

}