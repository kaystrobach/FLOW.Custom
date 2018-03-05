<?php
namespace KayStrobach\Custom\Utility;

use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Flow\Utility\MediaTypes;
use TYPO3\Flow\Resource\Exception as ResourceException;

class MediaTypeUtility {

    /**
     * @Flow\Inject()
     * @var ResourceManager
     */
    protected $resourceManager;

	public function getMediaTypeFromResource(\TYPO3\Flow\Resource\Resource $resource) {
		if(function_exists('finfo_open')) {

			try {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $tempPathAndFileName = $resource->createTemporaryLocalCopy();
                $mimetype = $finfo->file($tempPathAndFileName);
                unlink($tempPathAndFileName);
            } catch (ResourceException $e) {
			    // do nothing
            }
			if ($mimetype !== FALSE) {
				return $mimetype;
			}
		}
		if(function_exists('mime_content_type')) {
            $tempPathAndFileName = $resource->createTemporaryLocalCopy();
		    $mimetype = mime_content_type($tempPathAndFileName);
		    unlink($tempPathAndFileName);
		}
		return MediaTypes::getMediaTypeFromFilename('x.' . $resource->getFileExtension());
	}
}