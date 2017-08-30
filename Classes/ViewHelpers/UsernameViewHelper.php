<?php


namespace KayStrobach\Custom\ViewHelpers;

use Neos\Flow\Annotations as Flow;

class UsernameViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @Flow\Inject
	 * @var \Neos\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * Condition for Context
	 *
	 * @param string $context
	 * @return string
	 */
	public function render() {
		$accountObject = $this->authenticationManager->getSecurityContext()->getAccount();
		if ($accountObject) {
			return $accountObject->getAccountIdentifier();
		}
		return '';
	}
}
