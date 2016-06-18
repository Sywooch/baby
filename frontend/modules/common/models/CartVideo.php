<?php

namespace app\modules\common\models;

use metalguardian\fileProcessor\models\File;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%cart_video}}".
 *
 * @property integer $id
 * @property integer $mp4_video_id
 * @property integer $webm_video_id
 * @property string $created
 * @property string $modified
 */
class CartVideo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart_video}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getMp4File()
    {
        return $this->hasOne(File::className(), ['id' => 'mp4_video_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getWebMFile()
    {
        return $this->hasOne(File::className(), ['id' => 'webm_video_id']);
    }
}
