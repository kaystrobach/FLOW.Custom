<?php

namespace KayStrobach\Custom\Validation\Validator\File;
use TYPO3\Flow\Resource\Resource;
use TYPO3\Flow\Validation\Validator\AbstractValidator;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class MimeTypeValidator
 *
 * Checks wether a resources file is of a given mimetype (by content not extension)
 *  - allowedMimeTypes
 *
 * It is preconfigured for most common image formats
 *
 * The validator is named
 *  - KayStrobach.Custom:File\MimeType
 *
 */
class MimeTypeValidator extends AbstractValidator {

	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'allowedMimeTypes' => array(array('image/png', 'image/tiff', 'image/jpeg'), 'contains the allowed mimetypes', 'array'),
	);

	/**
	 * @Flow\Inject()
	 * @var \KayStrobach\Custom\Utility\MediaTypeUtility
	 */
	protected $mediaTypeUtility;

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

		$fileMimeType = $this->mediaTypeUtility->getMediaTypeFromResource($value);

		if(!in_array($fileMimeType, $this->options['allowedMimeTypes'])) {
			$this->addError(
				'The given asset was not of type %1$s but is of type %2$s',
				1433343575,
				array(
					implode(', ', $this->options['allowedMimeTypes']),
					$fileMimeType
				)
		);
		}

	}
}