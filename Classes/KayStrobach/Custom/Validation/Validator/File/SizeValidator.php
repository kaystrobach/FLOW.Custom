<?php

namespace KayStrobach\Custom\Validation\Validator\File;
use TYPO3\Flow\Resource\Resource;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 * Class SizeValidator
 *
 * Checks wether a resources file size is between given values,
 * therefore you need to supply a
 *  - minimum
 *  - maximum
 *
 * The validator is named
 *  - KayStrobach.Custom:File\Size
 *
 * @package KayStrobach\Custom\Validation\Validator\File
 */
class SizeValidator extends AbstractValidator {
	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'minimum' => array(0, 'The minimum value to accept', 'integer'),
		'maximum' => array(PHP_INT_MAX, 'The maximum value to accept', 'integer')
	);

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param mixed $value
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException if invalid validation options have been specified in the constructor
	 */
	protected function isValid($value) {
		if(!($value instanceof Resource)) {
			$this->addError('Given value is no Flow Resource', 1433344498);
		}

		$size = @filesize($value->getUri());

		$minimum = $this->options['minimum'];
		$maximum = $this->options['maximum'];
		if ($minimum > $maximum) {
			$x = $minimum;
			$minimum = $maximum;
			$maximum = $x;
		}

		if ($size < $minimum || $size > $maximum) {
			$this->addError('Filesize has to be between %1$d and %2$d.', 1221561046, array($minimum, $maximum));
		}
	}
}