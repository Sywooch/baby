<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use metalguardian\fileProcessor\helpers\FPM;

/**
 * Class SmartFPM
 * @package frontend\components
 */
class SmartFPM extends FPM
{
    /**
     * @param $id
     * @param $module
     * @param $size
     *
     * @return null|string|void
     */
    public static function src($id, $module, $size)
    {
        $size = IS_MOBILE ? $size.'Mobile' : $size;

        return parent::src($id, $module, $size);
    }
}
