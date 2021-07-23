<?php


namespace KayStrobach\Custom\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Http\Request;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Utility\ObjectAccess;

class SortViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper
{
    /**
     * NOTE: This property has been introduced via code migration to ensure backwards-compatibility.
     * @see AbstractViewHelper::isOutputEscapingEnabled()
     * @var boolean
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments(); // TODO: Change the autogenerated stub
        $this->registerArgument('objects', QueryResultInterface::class, 'the results', true);
        $this->registerArgument('as', 'string', 'the results', true);
        $this->registerArgument('sortBy', 'string', 'fiedl to order', true);
        $this->registerArgument('order', 'string', 'the order', true);
        $this->registerArgument('queryPartSortBy', 'string', 'the results', false, 'sortBy');
        $this->registerArgument('queryPartOrder', 'string', 'the results', false, 'order');
    }

    /**
     *
     * Wrap the results to be sorted by
     * <code>
     *   <custom:sort objects="{objects}" as="sortedObjects" order="DESC">
     *     // use something like the follow,ing VH
     *     <f:for each ...>
     *     </f:for>
     *   </custom:sort>
     * </code>
     *
     * to change the sorting you can easily use a f:link.action VH
     * <code>
     *   <f:link.action action=" ... " addQueryString="TRUE" additionalParams="{sortBy:'field', order:'order'}">
     *     some nice text
     *   </f:link>
     * </code>
     *
     * @return string
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception\InvalidVariableException
     */
    public function render()
    {
        $request = $this->controllerContext->getRequest();
        $sortBy = $request->hasArgument($this->arguments['queryPartSortBy']) ? $request->getArgument($this->arguments['queryPartSortBy']) : $this->arguments['sortBy'];
        $order = $request->hasArgument($this->arguments['queryPartOrder']) ? $request->getArgument($this->arguments['queryPartOrder']) : $this->arguments['order'];

        $result = $this->arguments['objects'];

        if ($result instanceof QueryResultInterface) {
            $result = $this->sortObjects($this->arguments['objects'], $sortBy, $order);
        } elseif (is_array($this->arguments['objects'])) {
            $result = $this->sortArrays($this->arguments['objects'], $sortBy, $order);
        }

        $this->templateVariableContainer->add($this->arguments['as'], $result);
        $buffer = $this->renderChildren();
        $this->templateVariableContainer->remove($this->arguments['as']);
        return $buffer;
    }

    protected function sortObjects(QueryResultInterface $objects, $sortBy, $order)
    {
        $query = $objects->getQuery();
        $query->setOrderings(
            array(
                $sortBy => $order === 'DESC' ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING
            )
        );
        return $query->execute();
    }

    protected function sortArrays($objects, $sortBy, $order)
    {
        usort(
            $objects,
            function ($a, $b) use ($sortBy) {
                return ObjectAccess::getProperty($a, $sortBy) < ObjectAccess::getProperty($b, $sortBy);
            }
        );
        if ($order === 'DESC') {
            return $objects;
        } else {
            return array_reverse($objects);
        }
    }
}
