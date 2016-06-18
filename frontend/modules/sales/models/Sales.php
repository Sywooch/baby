<?php

namespace frontend\modules\sales\models;

use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sales}}".
 *
 * @property integer $id
 * @property integer $image_id
 * @property integer $image_bottom_id
 * @property string $label
 * @property string $alias
 * @property string $content
 * @property string $description
 * @property integer $type
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property SalesLang[] $salesLangs
 */
class Sales extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'type', 'visible', 'position'], 'integer'],
            [['label', 'href', 'created', 'modified'], 'required'],
            [['content'], 'string'],
            [['created', 'modified'], 'safe'],
            [['label', 'href'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => SalesLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return ['label', 'content', 'description'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_id' => 'Image ID',
            'label' => 'Заголовок',
            'content' => 'Текст акции',
            'type' => 'Тип шаблона',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesLangs()
    {
        return $this->hasMany(SalesLang::className(), ['model_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getViewAllSalesRoute()
    {
        return ['/sales/sales/index'];
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getViewUrl($params = [])
    {
        return static::createUrl('/sales/sales/view', $params);
    }

    /**
     * @return string
     */
    public function getDescriptionForSeo()
    {
        $descLength = strlen($this->description);
        if (!$descLength) {
            return null;
        }

        return strlen($this->description) <= 70
            ? $this->description
            : mb_substr($this->description, 0, 70, 'UTF-8');
    }
}
