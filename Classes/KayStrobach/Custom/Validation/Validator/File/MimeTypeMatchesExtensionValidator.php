<?php

namespace KayStrobach\Custom\Validation\Validator\File;
use TYPO3\Flow\Resource\Resource;
use TYPO3\Flow\Validation\Validator\AbstractValidator;
use TYPO3\Flow\Utility\MediaTypes;
use TYPO3\Flow\Annotations as Flow;


/**
 * Class MimeTypeMatchesExtensionValidator
 *
 * Checks wether the file extensions mimetype matches the mimetype detected by the content
 *
 * The validator is named
 *  - KayStrobach.Custom:File\MimeTypeMatchesExtension
 *
 */
class MimeTypeMatchesExtensionValidator extends AbstractValidator {
	/**
	 * @Flow\Inject
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
		$fileExtensionMimetype = MediaTypes::getMediaTypeFromFilename('x.' . $value->getFileExtension());

		if($fileMimeType !== $fileExtensionMimetype) {
			$this->addError(
				'The given file has extension %1$s and should be of type %2$s, but is of type %3$s',
				1433343575,
				array(
					$value->getFileExtension(),
					$fileExtensionMimetype,
					$fileMimeType
				)
			);
		}

	}
}