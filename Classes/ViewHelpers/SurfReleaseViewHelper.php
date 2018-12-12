<?php

namespace KayStrobach\Custom\ViewHelpers;

use Neos\Flow\Core\ApplicationContext;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Utility\Environment;

class SurfReleaseViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper {
    /**
     * Show release string based on symlink
     *
     * @param string $format
     * @return string
     * @throws \Exception
     */
	public function render($format = 'd.m.Y H:i:s') {
		$output = $this->getContext() . ': ';
	    $date = $this->extractDateFromRevisionFile();
	    if ($date === null) {
	        $date = $this->extractDateFromSurfPath();
        }
	    if ($date === null) {
	        $date = new \DateTime('now');
        }

	    $output.= $date->format($format);
	    $output.= $this->extractRevisionFileContent();

		return $output;
	}

    /**
     * @return \Neos\Flow\Core\ApplicationContext
     */
	protected function getContext(): ApplicationContext
    {
        $objectManager = Bootstrap::$staticObjectManager;
        $environment = $objectManager->get(Environment::class);
        return $environment->getContext();

    }

	protected function extractDateFromRevisionFile()
    {
        if (!file_exists(FLOW_PATH_ROOT . '/REVISION')) {
            return null;
        }
        $dt = new \DateTime('now');
        $dt->setTimestamp(filemtime(FLOW_PATH_ROOT . '/REVISION'));
        return $dt;
    }

	protected function extractDateFromSurfPath()
    {
        $link = basename(FLOW_PATH_ROOT);
        if(((integer) $link !== 0) && (strlen($link) === 14)) {
            $date = \DateTime::createFromFormat(
                'YmdHis',
                $link
            );
            return $date;

        }
        return null;
    }

    protected function extractRevisionFileContent()
    {
        if(is_file(FLOW_PATH_ROOT . '/REVISION')) {
            return ' / @Rev.: ' . file_get_contents(FLOW_PATH_ROOT . '/REVISION');
        }
        return '';
    }
}
