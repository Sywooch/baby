<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use omgdef\multilingual\MultilingualQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class FrontModel
 * @package frontend\components
 */
class FrontModel extends ActiveRecord
{
    /**
     * @param $route
     * @param $params
     *
     * @return string
     */
    public static function createUrl($route, $params)
    {
        return Url::to(ArrayHelper::merge(
            [$route],
            $params
        ));
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        $model = new static();

        if ($model->getBehavior('ml')) {
            $q = new MultilingualQuery(get_called_class());
            $q->languageField = 'lang_id';
            $q->localized();
            return $q;
        }

        return parent::find();
    }
}
