<?php
 
namespace KayStrobach\Custom\Aspects;
 
use TYPO3\Flow\Annotations as Flow;
 
/**
 * Wraps around the \TYPO3\Flow\Cli\Response
 *
 * @Flow\Aspect
 */
class AroundCliResponseAspect {
	/**
	 * Directly outputs all the data instead of storing it in the buffer
	 *
	 * @param  \TYPO3\Flow\AOP\JoinPointInterface $joinPoint The current join point
	 * @return mixed Result of the target method
	 * @Flow\Around("method(TYPO3\Flow\Cli\Response->appendContent())")
	 */
	public function output(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
		//$result = $joinPoint->getAdviceChain()->proceed($joinPoint);
		#return $result;
		echo $joinPoint->getMethodArgument('content');
	}
}
