<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 18.11.14
 * Time: 09:55
 */

namespace KayStrobach\Custom\ViewHelpers\Widget;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

class AboutViewHelper extends AbstractViewHelper {
    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the render-result of this ViewHelper
     * @see isOutputEscapingEnabled()
     *
     * @var boolean
     * @api
     */
    protected $escapeOutput = false;

    /**
	 * @return string
	 */
	public function render() {
		if(!file_exists(FLOW_PATH_ROOT . 'composer.lock')) {
			return 'Missing composer.lock file';
		}

		$composerData = json_decode(file_get_contents(FLOW_PATH_ROOT . 'composer.lock'));

		$buffer = '';

		foreach($composerData->packages as $package) {
			$buffer .= '<li class="list-group-item">';
			$buffer .= $package->name . ' (' . $package->version . ')';
			if(property_exists($package ,'license')) {
				$buffer .= '<span class="badge">' . $package->license[0] . '</span>';
			}
			$buffer .= '</li>';
		}

		return '<ul class="list-group">' . $buffer . '</ul>';
	}
} 