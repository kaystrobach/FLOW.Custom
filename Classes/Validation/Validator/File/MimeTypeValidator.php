<?php

namespace KayStrobach\Custom\Validation\Validator\File;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\Validation\Validator\AbstractValidator;
use Neos\Flow\Annotations as Flow;

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
     * Check if $value is valid. If it is not valid, needs to add an error
     * to Result.
     *
     * @param mixed $value
     * @return void
     */
	protected function isValid($value) {

		if(!($value instanceof PersistentResource)) {
			$this->addError('Given value is no Flow Resource', 1433344498);
		}

		$fileMimeType = $value->getMediaType();

		if(!\in_array($fileMimeType, $this->options['allowedMimeTypes'], true)) {
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
