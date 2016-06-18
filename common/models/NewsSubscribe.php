<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%news_subscribe}}".
 *
 * @property integer $id
 * @property string $email
 * @property string $created
 * @property string $modified
 */
class NewsSubscribe extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_subscribe}}';
    }
}
