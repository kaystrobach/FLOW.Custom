<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 03.03.15
 * Time: 13:28
 */

namespace KayStrobach\Custom\ViewHelpers\Arrays;


use Neos\FluidAdaptor\Core\ViewHelper\AbstractConditionViewHelper;

class InViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments(); // TODO: Change the autogenerated stub
        $this->registerArgument('needle', 'string', 'thing to search', true);
        $this->registerArgument('haystack', 'string', 'the hay stack', true);
        $this->registerArgument('strict', 'boolean', 'type safe?', false, true);
    }

    /**
     * @return string
     */
    public function render()
    {
        if (is_array($this->arguments['haystack']) && in_array($this->arguments['needle'], $this->arguments['haystack'], $this->arguments['strict'])) {
            return $this->renderThenChild();
        }
        return $this->renderElseChild();
    }
}
