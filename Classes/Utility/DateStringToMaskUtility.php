<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 09.11.15
 * Time: 16:01
 */

namespace KayStrobach\Custom\Utility;


class DateStringToMaskUtility
{

    protected $map = array(
        'd' => '99',
        'D' => 'www',
        'm' => '99',
        'M' => 'www',
        'Y' => '9999',
        'y' => '99',
        'a' => 'ww',
        'A' => 'ww',
        'B' => '999',
        'h' => '99',
        'H' => '99',
        'i' => '99',
        's' => '99'
    );

    /**
     * @param string $string
     * @return string
     */
    public function convert($string)
    {
        foreach($this->map as $key => $item) {
            $string = str_replace($key, $item, $string);
        }
        return $string;
    }

    public function convertToUnderscores($string)
    {
        $string = $this->convert($string);
        $string = str_replace('9', '_', $string);
        $string = str_replace('w', '_', $string);
        return $string;
    }
}