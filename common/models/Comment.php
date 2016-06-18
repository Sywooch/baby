<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $article_id
 * @property string $content
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     *
     */
    const STATUS_VISIBLE = 1;
    const STATUS_NOT_VISIBLE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_NOT_VISIBLE => 'Не виден',
            static::STATUS_VISIBLE => 'Виден',
        ];
    }

    /**
     * @param $id
     * @return null
     */
    public static function getStatus($id)
    {
        $statusList = static::getStatusList();

        return isset($statusList[$id]) ? $statusList[$id] : null;
    }
}
