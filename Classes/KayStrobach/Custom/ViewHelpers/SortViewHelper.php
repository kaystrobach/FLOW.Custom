<?php


namespace KayStrobach\Custom\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Flow\Reflection\ObjectAccess;

class SortViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
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
	 * @param QueryResultInterface $objects
	 * @param $as
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @return string
	 */
	public function render($objects, $as, $sortBy, $order) {
		$this->controllerContext->getArguments()->getArgument('sortBy');
		$this->controllerContext->getArguments()->getArgument('order');

		if($objects instanceof QueryResultInterface) {
			$result = $this->sortObjects($objects, $as, $order);
		} elseif(is_array($objects)) {
			$result = $this->sortArrays($objects, $as, $order);
		}

		$this->viewHelperVariableContainer->add(__CLASS__, $as, $result);
		$buffer = $this->renderChildren();
		$this->viewHelperVariableContainer->remove(__CLASS__, $as);
		return $buffer;
	}

	protected function sortObjects(QueryResultInterface $objects, $sortBy, $order) {
		return $objects->getQuery()->setOrderings(
			array(
				$sortBy => $order === 'DESC' ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING
			)
		)->execute();
	}

	protected function sortArrays($objects, $sortBy, $order) {
		usort(
			$objects,
			function($a, $b) use($sortBy) {
				return ObjectAccess::getProperty($a, $sortBy) < ObjectAccess::getProperty($b, $sortBy);
			}
		);
		if($order === 'DESC') {
			return $objects;
		} else {
			return array_reverse($objects);
		}
	}
}
