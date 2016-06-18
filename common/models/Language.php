<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%language}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $code
 * @property string $locale
 * @property integer $visible
 * @property integer $default
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class Language extends ActiveRecord
{
    /**
     * @var Language
     */
    public static $current = null;

    /**
     * @var array
     */
    public static $languageModels = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @return mixed
     */
    public static function getCurrent()
    {
        if (static::$current === null) {
            static::$current = static::getDefaultLang();
        }
        return static::$current;
    }

    /**
     * @param null $code
     */
    public static function setCurrent($code = null)
    {
        $language = static::getLangByUrl($code);
        static::$current = ($language === null) ? static::getDefaultLang() : $language;
        Yii::$app->language = self::$current->locale;
    }

    /**
     * @return mixed
     */
    public static function getDefaultLang()
    {
        return static::getDb()->cache(
            function ($db) {
                return static::find()->where('is_default = :default', [':default' => 1])->one();
            },
            7200
        );
    }

    /**
     * @param null $code
     *
     * @return array|null|ActiveRecord
     */
    public static function getLangByUrl($code = null)
    {
        if ($code === null) {
            return null;
        } else {
            $language = static::find()->where('code = :code', [':code' => $code])->one();
            if ($language === null) {
                return null;
            } else {
                return $language;
            }
        }
    }

    /**
     * @return array
     */
    public static function getLangModels()
    {
        if (empty(static::$languageModels)) {
            static::$languageModels = static::find()->where(['visible' => 1])->all();
        }

        return static::$languageModels;
    }

    /**
     * @return array
     */
    public static function getLangMenuItems()
    {
        $return = [];

        $current = static::getCurrent();
        $langModels = static::getLangModels();
        if ($current) {
            foreach ($langModels as $k => $lModel) {
                if ($lModel->code == $current->code) {
                    unset($langModels[$k]);
                }
            }
        }

        foreach ($langModels as $lang) {
            $return[] = [
                'label' => $lang->label,
                'url' => '/' . $lang->code . \Yii::$app->getRequest()->getLangUrl()
            ];
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getLangList()
    {
        $langModels = static::getLangModels();

        if (!empty($langModels)) {
            return ArrayHelper::map($langModels, 'locale', 'label');
        }
    }
}
