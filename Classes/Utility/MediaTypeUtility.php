<?php
namespace KayStrobach\Custom\Utility;

use Neos\Utility\MediaTypes;

class MediaTypeUtility {

	public static function getMediaTypeFromResource(\Neos\Flow\ResourceManagement\PersistentResource $resource) {
        return $resource->getMediaType();
	}
}
