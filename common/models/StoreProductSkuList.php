<?php

namespace common\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "{{%store_product_sku_list}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $sku_prefix
 * @property integer $sku
 */
class StoreProductSkuList extends \yii\db\ActiveRecord
{
    public function init()
    {
        parent::init();

        $this->sku_prefix = 'A';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_sku_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'sku_prefix', 'sku'], 'required'],
            [['product_id', 'sku'], 'integer'],
            [['sku_prefix'], 'string', 'max' => 255],
            [['sku_prefix', 'sku'], 'unique', 'targetAttribute' => ['sku_prefix', 'sku'], 'message' => 'The combination of Sku Prefix and Sku has already been taken.'],
            [['id', 'product_id', 'sku_prefix', 'sku'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'sku_prefix' => 'Sku Prefix',
            'sku' => 'Sku',
        ];
    }

    /**
     * @param $skuToCheck
     *
     * @return string
     */
    public static function getNewSku($skuToCheck)
    {
        $model = new self;

        if (!$skuToCheck) {
            return $model->getSku();
        } else {
            $existSku = $model->isExistSku($skuToCheck);

            if ($existSku) {
                $sku = $model->getSku();
                \Yii::$app->getSession()->addFlash('info', 'Такой артикул был занят, мы присвоили этому товару артикул '.$sku);
                return $sku;
            } else {
                return $skuToCheck;
            }
        }
    }

    /**
     * @param $skuToCheck
     * @param $productId
     */
    public static function saveNewSku($skuToCheck, $productId)
    {
        $newSku = new StoreProductSkuList();
        if (!$newSku->isExistSku($skuToCheck)) {
            $newSku->product_id = $productId;
            $newSku->sku_prefix = substr($skuToCheck, 0, 1);
            $newSku->sku = substr($skuToCheck, 1);
            $newSku->save(false);
        }
    }

    /**
     * @return string
     */
    public function getSku()
    {
        $this->getLatestSku();
        return $this->sku_prefix.$this->sku;
    }

    /**
     * @return string
     */
    public function getLatestSku()
    {
        $latestSkuNum = (new Query())
            ->select('MAX(sku) as maxSku')
            ->where('sku_prefix = :skup', [':skup' => $this->sku_prefix])
            ->from(static::tableName())
            ->scalar();

        if (!$latestSkuNum) {
            $latestSkuNum = 0;
        }

        $this->sku = sprintf("%'.06d", $latestSkuNum+1);
    }

    /**
     * @param $skuToCheck
     *
     * @return bool
     */
    protected function isExistSku($skuToCheck)
    {
        $sku_prefix = substr($skuToCheck, 0, 1);
        $sku = (int)substr($skuToCheck, 1);
        $existSku = (new Query())
            ->from(static::tableName())
            ->where('sku_prefix = :skup', [':skup' => $sku_prefix])
            ->andWhere('sku = :sku', [':sku' => $sku])
            ->count();

        return $existSku ? true : false;
    }
}
