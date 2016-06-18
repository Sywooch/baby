<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

/**
 * Class SocialAuthHelper
 * @package frontend\components
 */
class SocialAuthHelper
{
    /**
     * @param array $attributes
     * @return bool
     */
    public static function getEmail(array $attributes)
    {
        $email = false;

        switch (true) {
            //Google OAuth
            case isset($attributes['emails'][0]['value']):
                $email = $attributes['emails'][0]['value'];
                break;
            //Facebook
            case isset($attributes['email']):
                $email = $attributes['email'];
                break;
        }

        return $email;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public static function getName(array $attributes)
    {
        $name = '';

        switch (true) {
            //Google OAuth
            case isset($attributes['name']['givenName']):
                $name = $attributes['name']['givenName'];
                break;
            //Facebook
            case isset($attributes['name']):
                $name = explode(' ', $attributes['name']);
                $name = $name[0];
                break;
            //VK
            case isset($attributes['first_name']):
                $name = $attributes['first_name'];
                break;
        }

        return $name;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public static function getSurname(array $attributes)
    {
        $surname = '';

        switch (true) {
            //Google OAuth
            case isset($attributes['name']['familyName']):
                $surname = $attributes['name']['familyName'];
                break;
            //Facebook
            case isset($attributes['name']):
                $surname = explode(' ', $attributes['name']);
                $surname = isset($surname[1]) ? $surname[1] : '';
                break;
            //VK
            case isset($attributes['last_name']):
                $surname = $attributes['last_name'];
                break;
        }

        return $surname;
    }


    public static function getAvatarImage(array $attributes) {
        $avatarImg = '';

        switch (true) {
            //Google OAuth
            case (isset($attributes['image']['url'], $attributes['image']['isDefault']) &&
                !$attributes['image']['isDefault']):
                $avatarImg = str_replace('sz=50', 'sz=120', $attributes['image']['url']);
                break;
            //Facebook
            //if isset picture and its not the default avatar
            case (isset($attributes['picture']['data']['url'], $attributes['picture']['data']['is_silhouette']) &&
            !$attributes['picture']['data']['is_silhouette']):
                $avatarImg = $attributes['picture']['data']['url'];
                break;
            //VK
            case isset($attributes['photo_big']):
                $avatarImg = $attributes['photo_big'];
                break;
        }

        return $avatarImg;
    }

    /**
     * @param array $attributes
     * @return int|null
     * @throws \yii\base\Exception
     */
    public static function getAvatarImageId(array $attributes) {
        $avatarUrl = static::getAvatarImage($attributes);
        $id = null;

        if (!empty($avatarUrl)) {
            $uploadDir = \Yii::$app->getBasePath() . '/web/uploads/social_profile_avatar/';
            $avatarName = explode('/', $avatarUrl);
            //remove all unneeded params in file path
            $avatarName = strtok(end($avatarName), '?');

            file_put_contents($uploadDir . $avatarName, fopen($avatarUrl, 'r'));

            $avatarInfo = pathinfo($uploadDir . $avatarName);
            $avatarBaseName = $avatarInfo['basename'];
            $avatarExt = $avatarInfo['extension'];

            $model = new \metalguardian\fileProcessor\models\File();
            $model->extension = $avatarExt;
            $model->base_name = $avatarBaseName;
            $model->save(false);

            $directory = \metalguardian\fileProcessor\helpers\FPM::getOriginalDirectory($model->id);

            \yii\helpers\FileHelper::createDirectory($directory, 0777, true);

            $newFileName =
                $directory
                . DIRECTORY_SEPARATOR
                . \metalguardian\fileProcessor\helpers\FPM::getOriginalFileName(
                    $model->id,
                    $avatarBaseName,
                    $avatarExt
                );

            rename($uploadDir . $avatarName, $newFileName);

            $id = $model->id;
        }

        return $id;
    }
}
