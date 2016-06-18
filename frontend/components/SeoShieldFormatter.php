<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\components;

use yii\web\ResponseFormatterInterface;

/**
 * Class SeoshieldFormatter
 *
 * @package frontend\components
 */
class SeoShieldFormatter implements ResponseFormatterInterface
{
    /**
     * @inheritdoc
     */
    public function format($response)
    {
        if ($response->data !== null) {
            $response->content = seo_shield_out_buffer($response->data);
        }
    }
}
