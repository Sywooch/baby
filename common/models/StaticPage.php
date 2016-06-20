<?php

namespace common\models;

use common\models\Language;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "static_page".
 *
 * @property integer $id
 * @property string $label
 * @property string $alias
 * @property string $content
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class StaticPage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_page';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Url::to(['/static-page/index', 'alias' => $this->alias]);
    }

    /**
     * @return string
     */
    public static function getAboutUrl()
    {
        /** @var static $model */
        $model = static::find()
            ->where(['id' => 1, 'visible' => 1])
            ->one();
        if ($model) {
            return $model->getUrl();
        }
        
        return '#';
    }

    /**
     * @return string
     */
    public static function getContactsUrl()
    {
        /** @var static $model */
        $model = static::find()
            ->where(['id' => 2, 'visible' => 1])
            ->one();
        if ($model) {
            return $model->getUrl();
        }

        return '#';
    }
}
