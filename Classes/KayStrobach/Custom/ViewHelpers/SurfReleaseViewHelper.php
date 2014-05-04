<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 04.05.14
 * Time: 12:52
 */

namespace KayStrobach\Custom\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

class SurfReleaseViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Core\Bootstrap
	 */
	protected $bootstrap;

	/**
	 * Show release string based on symlink
	 *
	 * @param string $format
	 * @return string
	 */
	public function render($format = 'd.m.Y H:i:s') {
		if($this->bootstrap->getContext()->isProduction()) {
			$link = basename(FLOW_PATH_ROOT);
			if(((integer) $link !== 0) && (strlen($link) === 14)) {
				$date = \DateTime::createFromFormat(
					'YmdHis',
					$link
				);
				if($date === FALSE) {
					$output = 'Build: ' . $link;
				} else {
					$output = 'Build: ' . $date->format($format);
					$revisionFilename = FLOW_PATH_ROOT . '../' . $link . 'REVISION';
					if(is_file($revisionFilename)) {
						$output .= ' / @Rev.: ' . file_get_contents($revisionFilename);
					}
				}

			} else {
				return 'No TYPO3.Surf? Build: ' . $link;
			}
		} elseif($this->bootstrap->getContext()->isDevelopment()) {
			$output = 'Development Context';
		} else {
			$output = $this->renderChildren();
		}
		return $output;
	}
} 