<?php
namespace KayStrobach\Custom\Utility;

use TYPO3\Flow\Utility\MediaTypes;

class MediaTypeUtility {

	public static function getMediaTypeFromResource(\TYPO3\Flow\Resource\Resource $resource) {
		if(function_exists('finfo_open')) {
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$mimetype = $finfo->file($resource->getUri());
			if ($mimetype !== FALSE) {
				return $mimetype;
			}
		}
		if(function_exists('mime_content_type')) {
			return mime_content_type($resource->getUri());
		}
		return MediaTypes::getMediaTypeFromFilename('x.' . $resource->getFileExtension());
	}
}