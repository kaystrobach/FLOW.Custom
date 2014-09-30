<?php


namespace KayStrobach\Custom\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

class UsernameViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
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
