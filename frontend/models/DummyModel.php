<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\models;

use frontend\components\FrontModel;

/**
 * Class DummyModel
 * @package frontend\models
 */
class DummyModel extends FrontModel
{
    /**
     * @return array
     */
    public static function getShowroomRoute()
    {
        return ['/site/showroom'];
    }

    /**
     * @return array
     */
    public static function getDeliveryRoute()
    {
        return ['/site/delivery'];
    }

    /**
     * @return array
     */
    public static function getAboutUsRoute()
    {
        return ['/site/about'];
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getLoginLink($params = [])
    {
        return static::createUrl('/user/user/login', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getSignupLink($params = [])
    {
        return static::createUrl('/user/user/signup', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getPasswordResetLink($params = [])
    {
        return static::createUrl('/user/user/request-password-reset', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getPasswordResetSetNewPassworLink($params = [])
    {
        return static::createUrl('/user/user/reset-password', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getProfileLink($params = [])
    {
        return static::createUrl('/user/user/profile', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getFullFillProfileLink($params = [])
    {
        return static::createUrl('/user/user/full-fill-profile', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getProfileUpdateLink($params = [])
    {
        return static::createUrl('/user/user/profile-update', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getLogoutLink($params = [])
    {
        return static::createUrl('/user/user/logout', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getBlogUrl($params = [])
    {
        return static::createUrl('/site/blog', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getProfileUploadUrl($params = [])
    {
        return static::createUrl('/user/user/upload', $params);
    }

    /**
     * @param $params
     *
     * @return string
     */
    public static function getMyChicardiLink($params = [])
    {
        return \Yii::$app->user->isGuest
            ? static::getSignupLink($params)
            : static::getProfileLink($params);
    }
}
