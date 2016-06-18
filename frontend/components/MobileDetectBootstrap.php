<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use yii\base\BootstrapInterface;

/**
 * Class MobileDetectBootstrap
 *
 * @package frontend\components
 */
class MobileDetectBootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $detector = new \Mobile_Detect();
        $isMobile = $detector->isMobile();
        $isTablet = $detector->isTablet();

        defined('IS_MOBILE') or define('IS_MOBILE', $isMobile);
        defined('IS_TABLET') or define('IS_TABLET', $isTablet);


        if ($isMobile) {
            if ($app->hasModule('fileProcessor')) {
                $app->setModule(
                    'fileProcessor',
                    [
                        'class' => '\metalguardian\fileProcessor\Module',
                        'imageSections' => require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                                'common' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'image-resize-mobile-config.php')
                    ]
                );
            }
        }
    }
}
