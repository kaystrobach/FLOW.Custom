<?php
 
namespace KayStrobach\Custom\Aspects;
 
use Neos\Flow\Annotations as Flow;
 
/**
 * Wraps around the \Neos\Flow\Cli\Response
 *
 * @Flow\Aspect
 */
class AroundCliResponseAspect {
	/**
	 * Directly outputs all the data instead of storing it in the buffer
	 *
	 * @param  \Neos\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return mixed Result of the target method
	 * @Flow\Around("method(Neos\Flow\Cli\Response->appendContent())")
	 */
	public function output(\Neos\Flow\Aop\JoinPointInterface $joinPoint) {
		//$result = $joinPoint->getAdviceChain()->proceed($joinPoint);
		#return $result;
		echo $joinPoint->getMethodArgument('content');
	}
}
