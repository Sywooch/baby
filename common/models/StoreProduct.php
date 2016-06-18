<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%store_product}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $category_id
 * @property string $label
 * @property string $alias
 * @property string $announce
 * @property string $content
 * @property string $sku
 * @property string $price
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class StoreProduct extends ActiveRecord
{
    /**
     *
     */
    //const STATUS_ONLY_DIRECT = 0;
    /**
     *
     */
    const STATUS_AVAILABLE = 1;
    /**
     *
     */
    const STATUS_WAIT_FOR = 2;
    /**
     *
     */
    const STATUS_NOT_AVAILABLE = 3;

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_NOT_AVAILABLE => 'Нет в наличии',
            static::STATUS_AVAILABLE => 'В наличии',
            static::STATUS_WAIT_FOR => 'Ожидаем',
            //static::STATUS_ONLY_DIRECT => 'По ссылке'
        ];
    }

    /**
     * @param $statusId
     *
     * @return null
     */
    public static function getStatus($statusId)
    {
        $statusList = static::getStatusList();

        return isset($statusList[$statusId])
            ? $statusList[$statusId]
            : null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(StoreCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(StoreProductType::className(), ['id' => 'type_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип',
            'category_id' => 'Категория',
            'label' => 'Название',
            'alias' => 'Ссылка',
            'announce' => 'Краткое описание',
            'content' => 'Полное описание',
            'sku' => 'Артикул',
            'price' => 'Цена',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->where('entity_model_name = :emn', [':emn' => 'StoreProduct'])
            ->joinWith('file')
            ->orderBy(EntityToFile::tableName().'.position DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEav()
    {
        return $this->hasMany(StoreProductEav::className(), ['product_id' => 'id'])
            ->joinWith('attributeRel');
    }

    /**
     * @param bool $checkboxList
     *
     * @return array|string
     */
    public static function getExportColumns($checkboxList = true)
    {
        $mainCols = static::getExportHeaders();

        $eavCols = (new Query())
            ->from(StoreProductAttribute::tableName())
            ->select('label')
            ->orderBy('id')
            ->column();

        $totalCols = ArrayHelper::merge($mainCols, $eavCols);
        $totalCols = array_combine($totalCols, $totalCols);

        return $checkboxList
            ? Html::checkboxList('exportCols', $totalCols, $totalCols, ['separator' => '<br />'])
            : $totalCols;
    }

    /**
     * @param bool $onlyKeys
     *
     * @return array
     */
    public static function getExportHeaders($onlyKeys = false)
    {
        $mainCols = (new StoreProduct())->attributeLabels();
        unset($mainCols['id']);
        unset($mainCols['created']);
        unset($mainCols['modified']);

        return $onlyKeys
            ? array_keys($mainCols)
            : array_values($mainCols);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function getExportRow($attributes = [])
    {
        $headers = $this->prepareExportHeaders($attributes);

        $selfAttributes = $this->prepareSelfAttributes($headers);

        $relatedAttrs = $this->prepareRelatedAttributes($attributes);

        return ArrayHelper::merge($selfAttributes, $relatedAttrs);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function prepareExportHeaders($attributes)
    {
        $headers = static::getExportHeaders(true);

        if (!empty($attributes)) {
            $labels = static::attributeLabels();
            $attributesKeys = [];
            foreach ($attributes as $attr) {
                $keyInArray = array_search($attr, $labels);
                if ($keyInArray) {
                    $attributesKeys[] = $keyInArray;
                }
            }

            $headers = array_intersect($headers, $attributesKeys);
        }

        return $headers;
    }


    /**
     * @param array $columns
     *
     * @return array
     */
    protected function prepareSelfAttributes($columns)
    {
        $selfAttrs = $this->getAttributes($columns);

        foreach ($selfAttrs as $name => $value) {
            if ($name == 'type_id') {
                $selfAttrs[$name] = $this->type->label;
            }

            if ($name == 'category_id') {
                $selfAttrs[$name] = $this->category->label;
            }
        }

        return $selfAttrs;
    }


    /**
     * @param array $attributes
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function prepareRelatedAttributes($attributes)
    {
        $relatedAttrs = StoreProductEav::find()
            ->select(['*', 'store_product_attribute.label AS label'])
            ->joinWith('attributeRel')
            ->where('product_id = :tid', [':tid' => $this->id])
            ->orderBy('store_product_attribute.id')
            ->asArray()
            ->all();

        if (!empty($relatedAttrs)) {
            if (!empty($attributes)) {
                foreach ($relatedAttrs as $id => $rel) {
                    if (!in_array($rel['label'], $attributes)) {
                        unset($relatedAttrs[$id]);
                    }
                }
            }

            $relatedAttrs = ArrayHelper::map($relatedAttrs, 'label', 'value');
        }

        return $relatedAttrs;
    }
}
