<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%gift_request}}".
 *
 * @property integer $id
 * @property integer $sex
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $receiver
 * @property string $about_receiver
 * @property string $about_gift
 * @property string $gift_reason
 * @property integer $gift_budget
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class GiftRequest extends ActiveRecord
{
    /**
     *
     */
    const SEX_MALE = 1;
    /**
     *
     */
    const SEX_FEMALE = 2;
    /**
     *
     */
    const SEX_OTHER = 3;

    /**
     *
     */
    const BUDGET_ANY = 1;

    /**
     *
     */
    const BUDGET_SMALL = 2;

    /**
     *
     */
    const BUDGET_MIDDLE = 3;

    /**
     *
     */
    const BUDGET_BIG = 4;

    /**
     *
     */
    const STATUS_NEW = 1;

    /**
     *
     */
    const STATUS_HANDLED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_request}}';
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_NEW => 'Новый',
            static::STATUS_HANDLED => 'Обработан',
        ];
    }

    /**
     * @param $statusId
     *
     * @return string|null
     */
    public static function getStatus($statusId)
    {
        $statusList = static::getStatusList();

        return isset($statusList[$statusId]) ? $statusList[$statusId] : null;
    }

    /**
     * @return array
     */
    public static function getSexList()
    {
        return [
            static::SEX_MALE => Yii::t('frontend', 'get_gift_sex_male'),
            static::SEX_FEMALE => Yii::t('frontend', 'get_gift_sex_female'),
            static::SEX_OTHER => Yii::t('frontend', 'get_gift_sex_other'),
        ];
    }

    /**
     * @param $sexId
     *
     * @return string|null
     */
    public static function getSex($sexId)
    {
        $sexList = static::getSexList();

        return isset($sexList[$sexId]) ? $sexList[$sexId] : null;
    }

    /**
     * @return array
     */
    public static function getBudgetList()
    {
        return [
            static::BUDGET_ANY => Yii::t('frontend', 'get_gift_budget_any'),
            static::BUDGET_SMALL => Yii::t('frontend', 'get_gift_budget_small'),
            static::BUDGET_MIDDLE => Yii::t('frontend', 'get_gift_budget_middle'),
            static::BUDGET_BIG => Yii::t('frontend', 'get_gift_budget_big'),
        ];
    }

    /**
     * @param $budgetId
     *
     * @return string|null
     */
    public static function getBudget($budgetId)
    {
        $budgetList = static::getBudgetList();

        return isset($budgetList[$budgetId]) ? $budgetList[$budgetId] : null;
    }
}
