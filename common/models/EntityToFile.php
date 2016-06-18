<?php

namespace common\models;

use metalguardian\fileProcessor\models\File;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%entity_to_file}}".
 *
 * @property integer $id
 * @property string $entity_model_name
 * @property integer $entity_model_id
 * @property integer $file_id
 * @property string $temp_sign
 *
 * @property \metalguardian\fileProcessor\models\File $file
 */
class EntityToFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%entity_to_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_model_name', 'entity_model_id', 'file_id'], 'required'],
            [['entity_model_id', 'file_id'], 'integer'],
            [['entity_model_name', 'temp_sign'], 'string', 'max' => 255],
            ['position', 'default', 'value' => 0],
            ['temp_sign', 'default', 'value' => ''],
            [['id', 'entity_model_name', 'entity_model_id', 'file_id', 'temp_sign'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_model_name' => 'Entity Model Name',
            'entity_model_id' => 'Entity Model ID',
            'file_id' => 'File ID',
            'temp_sign' => 'Temp Sign',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }

    /**
     * @param $entityName
     * @param $entityId
     * @param $fileId
     * @param null $sign
     *
     * @return bool|EntityToFile
     */
    public static function add($entityName, $entityId, $fileId, $sign = null)
    {
        $model = new EntityToFile();
        $model->file_id = (int)$fileId;
        $model->entity_model_name = $entityName;
        if ($sign) {
            $model->temp_sign = $sign;
            $model->entity_model_id = 0;
        } else {
            $model->entity_model_id = $entityId ? $entityId : 0;
        }

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * @param $entityModelName
     * @param $entityModelId
     */
    public static function deleteImages($entityModelName, $entityModelId)
    {
        static::deleteAll(
            'entity_model_name = :enm AND entity_model_id = :emi',
            [':enm' => $entityModelName, ':emi' => $entityModelId]
        );
    }
}
