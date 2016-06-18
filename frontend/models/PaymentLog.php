<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%payment_log}}".
 *
 * @property integer $id
 * @property string $text
 * @property string $created
 */
class PaymentLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['created'], 'required'],
            [['created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст',
            'created' => 'Создано',
        ];
    }

    /**
     * @param $text
     */
    public static function add($text)
    {
        $log = new self();

        $log->text = $text;
        $log->created = date('Y-m-d H:i:s');
        $log->save(false);
    }
}
