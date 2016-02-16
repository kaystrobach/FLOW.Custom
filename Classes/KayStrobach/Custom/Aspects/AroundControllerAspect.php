<?php

namespace KayStrobach\Custom\Aspects;

use KayStrobach\Custom\View\JsendView;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Annotations as Flow;

/**
 * controller foo
 *
 * @Flow\Aspect
 */
class AroundControllerAspect
{
    /**
     * Directly outputs all the data instead of storing it in the buffer
     *
     * @Flow\Around("method(TYPO3\Flow\Mvc\Controller\AbstractController->redirectToUri())")
     *
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException
     *
     * @param  \TYPO3\Flow\AOP\JoinPointInterface $joinPoint The current join point
     * @return mixed Result of the target method
     *
     */
    public function output(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {

        /** @var ActionController $controller */
        $controller = $joinPoint->getProxy();
        $view = ObjectAccess::getProperty($controller, 'view', true);

        /** @var \TYPO3\Flow\Http\Response $response */
        $response = $controller->getControllerContext()->getResponse();

        if($view instanceof JsendView) {
            $view->assign('redirectTo', $joinPoint->getMethodArgument('uri'));
            $response->setContent($view->render());
            throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
        } else {
            return $joinPoint->getAdviceChain()->proceed($joinPoint);
        }
    }
}