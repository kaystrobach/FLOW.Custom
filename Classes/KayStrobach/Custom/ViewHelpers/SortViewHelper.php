<?php


namespace KayStrobach\Custom\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Flow\Reflection\ObjectAccess;

class SortViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * NOTE: This property has been introduced via code migration to ensure backwards-compatibility.
	 * @see AbstractViewHelper::isOutputEscapingEnabled()
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;
	
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
	 * @param string $as              alias in the fluid code
	 * @param string $sortBy          default sortby field
	 * @param string $order           default sortby order
	 * @param string $queryPartSortBy get param name to specify field for order
	 * @param string $queryPartOrder  get param name to specify direction
	 * @return string
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 */
	public function render($objects, $as, $sortBy, $order, $queryPartSortBy = 'sortBy', $queryPartOrder = 'order') {
		$request = Request::createFromEnvironment();
		$sortBy = $request->getArgument($queryPartSortBy) ? $request->getArgument($queryPartSortBy) : $sortBy;
		$order = $request->getArgument($queryPartOrder) ? $request->getArgument($queryPartOrder) : $order;

		$result = $objects;

		if($objects instanceof QueryResultInterface) {
			$result = $this->sortObjects($objects, $sortBy, $order);
		} elseif(is_array($objects)) {
			$result = $this->sortArrays($objects, $sortBy, $order);
		}

		$this->templateVariableContainer->add($as, $result);
		$buffer = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $buffer;
	}

	protected function sortObjects(QueryResultInterface $objects, $sortBy, $order) {
		$query = $objects->getQuery();
		$query->setOrderings(
			array(
				$sortBy => $order === 'DESC' ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING
			)
		);
		return $query->execute();
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
