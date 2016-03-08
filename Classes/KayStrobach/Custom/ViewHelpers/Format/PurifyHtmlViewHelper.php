<?php

namespace KayStrobach\Custom\ViewHelpers\Format;

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

class PurifyHtmlViewHelper extends AbstractViewHelper
{
    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the result of renderChildren() calls within this ViewHelper
     * @see isChildrenEscapingEnabled()
     *
     * Note: If this is NULL the value of $this->escapingInterceptorEnabled is considered for backwards compatibility
     *
     * @var boolean
     * @api
     */
    protected $escapeChildren = FALSE;

    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the render-result of this ViewHelper
     * @see isOutputEscapingEnabled()
     *
     * @var boolean
     * @api
     */
    protected $escapeOutput = FALSE;

    /**
     * @param string $value
     * @return string
     */
    public function render($value = NULL) {
        \HTMLPurifier_Bootstrap::registerAutoload();
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('URI.AllowedSchemes', array('data' => true));
        $purifier = \HTMLPurifier::getInstance($config);

        if($value === NULL) {
            return $purifier->purify($this->renderChildren());
        } else {
            return $purifier->purify($value);
        }
    }
}