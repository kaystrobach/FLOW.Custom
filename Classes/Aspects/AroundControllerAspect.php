<?php

namespace KayStrobach\Custom\Aspects;

use KayStrobach\Custom\View\JsendView;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Utility\ObjectAccess;
use Neos\Flow\Annotations as Flow;

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
     * @Flow\Around("method(Neos\Flow\Mvc\Controller\AbstractController->redirectToUri())")
     *
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Utility\Exception\PropertyNotAccessibleException
     *
     * @param  \Neos\Flow\Aop\JoinPointInterface $joinPoint The current join point
     * @return mixed Result of the target method
     *
     */
    public function output(\Neos\Flow\Aop\JoinPointInterface $joinPoint) {

        /** @var ActionController $controller */
        $controller = $joinPoint->getProxy();
        $view = ObjectAccess::getProperty($controller, 'view', true);

        /** @var \Neos\Flow\Http\Response $response */
        $response = $controller->getControllerContext()->getResponse();

        if($view instanceof JsendView) {
            $view->assign('redirectTo', $joinPoint->getMethodArgument('uri'));
            $response->setContent($view->render());
            throw new \Neos\Flow\Mvc\Exception\StopActionException();
        } else {
            return $joinPoint->getAdviceChain()->proceed($joinPoint);
        }
    }
}