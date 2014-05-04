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
		if($this->bootstrap->getContext()->isProduction() || 1) {
			$link = basename(FLOW_PATH_ROOT);
			if(((integer) $link !== 0) && (strlen($link) === 14)) {
				$date = \DateTime::createFromFormat(
					'Ymdhis',
					$link
				);
				if($date === FALSE) {
					$output = 'Production Build Number: ' . $link;
				} else {
					$output = 'Production build from: ' . $date->format($format);
				}

			} else {
				return 'not deployed with TYPO3.Surf? Buildname: ' . $link;
			}
		} elseif($this->bootstrap->getContext()->isDevelopment()) {
			$output = 'Development Context';
		} else {
			$output = $this->renderChildren();
		}
		return $output;
	}
} 