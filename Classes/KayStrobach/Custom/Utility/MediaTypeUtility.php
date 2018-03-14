<?php
namespace KayStrobach\Custom\Utility;

use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Utility\MediaTypes;
use Neos\Flow\ResourceManagement\Exception as ResourceException;
use Neos\Flow\Annotations as Flow;

class MediaTypeUtility {

    /**
     * @Flow\Inject()
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @param \Neos\Flow\ResourceManagement\PersistentResource $resource
     * @return string
     * @throws ResourceException
     */
	public function getMediaTypeFromResource(\Neos\Flow\ResourceManagement\PersistentResource $resource) {
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