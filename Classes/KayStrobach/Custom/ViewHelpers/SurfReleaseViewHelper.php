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
			if(@is_link(FLOW_PATH_FLOW)) {
				$link = readlink(FLOW_PATH_FLOW);
				$date = \DateTime::createFromFormat(
					'Ymdhis',
					substr($link, 3)
				);
				$output = 'Production Context, with last build from: ' . $date->format($format);
			} else {
				return 'not deployed with TYPO3.Surf?';
			}
		} elseif($this->bootstrap->getContext()->isDevelopment()) {
			$output = 'Development Contenxt';
		} else {
			$output = $this->renderChildren();
		}
		return $output;
	}
} 