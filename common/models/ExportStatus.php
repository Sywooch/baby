<?php

namespace common\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%export_status}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $is_exported
 * @property string $status
 * @property string $result_file_name
 * @property string $export_columns
 */
class ExportStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%export_status}}';
    }

    /**
     * @param $userId
     * @param $value
     * @param string $resultFileName
     * @param array $attributes
     */
    public static function updateStatus($userId, $value, $resultFileName = '', $attributes = [])
    {
        $status = self::find()
            ->where('user_id = :uid', [':uid' => $userId])
            ->one();

        if (!$status) {
            $status = new self();
            $status->user_id = $userId;
        }
        if ($value == 100) {
            $status->is_exported = 1;
        } else {
            $status->is_exported = 0;
        }
        if (!empty($attributes)) {
            $status->export_columns = json_encode($attributes);
        }

        $status->result_file_name = $resultFileName;
        $status->status = $value;
        $status->save(false);
    }

    /**
     * @param $userId
     *
     * @return array|mixed
     */
    public static function getExportColumns($userId)
    {
        $columns = [];

        $status = self::find()
            ->where('user_id = :uid', [':uid' => $userId])
            ->where('status = 0')
            ->one();

        if ($status) {
            $columns = json_decode($status->export_columns, true);
        }

        return $columns;
    }
}
