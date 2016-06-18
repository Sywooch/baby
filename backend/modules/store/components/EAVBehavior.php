<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\components;

use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class EAVBehavior
 * @package backend\modules\store\components
 */
class EAVBehavior extends Behavior
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @var array
     */
    public $multiLangOptions = [];

    /**
     * @var array
     */
    public $multiLangConfig = [];

    /**
     * Primary key column name in the main table
     * @var string
     */
    public $pk = 'id';

    /**
     * @var
     */
    public $tableName;

    /**
     * @var
     */
    public $entityColumn;

    /**
     * @var
     */
    public $attributeColumn;

    /**
     * @var
     */
    public $valueColumn;

    public function init()
    {
        if (is_null($this->tableName) ||
            is_null($this->entityColumn) ||
            is_null($this->attributeColumn) ||
            is_null($this->valueColumn)) {
            throw new InvalidConfigException('You must specify "tableName", "entityColumn", "attributeColumn" and "valueColumn"');
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterFind',
        ];
    }

    /**
     * Get all EAV attributes for current AR record
     *
     * @param $event
     */
    public function afterFind($event)
    {
        $this->options = (new Query())
            ->select([$this->attributeColumn, $this->valueColumn])
            ->from($this->tableName)
            ->where([$this->entityColumn => $this->owner->{$this->pk}])
            ->all();

        $this->options = ArrayHelper::map($this->options, 'attribute_id', 'value');

        $this->loadMultiLangAttributes();
    }

    /**
     * Save entered data to EAV table
     */
    public function afterSave($event)
    {
        $this->deleteOldRecords();
        $data = [];

        foreach ($this->options as $attributeId => $value) {
            if ($value != '') {

                if (is_array($value)) {
                    foreach ($value as $val) {
                        $data[] = [
                            $this->entityColumn => $this->owner->{$this->pk},
                            $this->attributeColumn => $attributeId,
                            $this->valueColumn => $val
                        ];
                    }
                } else {
                    $data[] = [
                        $this->entityColumn => $this->owner->{$this->pk},
                        $this->attributeColumn => $attributeId,
                        $this->valueColumn => $value
                    ];
                }

            }
        }

        if (!empty($data)) {
            \Yii::$app->db->createCommand()
                ->batchInsert($this->tableName, [$this->entityColumn, $this->attributeColumn, $this->valueColumn], $data)
                ->execute();
        }

        $this->saveMultiLangAttributes();
    }


    /**
     * @return bool
     */
    public function isEavMultiLang()
    {
        $isMultiLangConfigProperlySet = isset(
            $this->multiLangConfig['langTable'],
            $this->multiLangConfig['defaultLang'],
            $this->multiLangConfig['langTableFk'],
            $this->multiLangConfig['langTableLanguageColumn']
            ) && (
                !empty($this->multiLangConfig['langTable']) &&
                !empty($this->multiLangConfig['defaultLang']) &&
                !empty($this->multiLangConfig['langTableFk']) &&
                !empty($this->multiLangConfig['langTableLanguageColumn'])
            );

        return $isMultiLangConfigProperlySet ? true : false;
    }

    protected function saveMultiLangAttributes()
    {
        $data = [];

        if ($this->isEavMultiLang()) {
            foreach ($this->multiLangOptions as $optionId => $optionLangValues) {
                $mainOption = (new Query())
                    ->select('id, '.$this->valueColumn)
                    ->from($this->tableName)
                    ->where([$this->entityColumn => $this->owner->{$this->pk}])
                    ->andWhere([$this->attributeColumn => $optionId])
                    ->one();

                if ($mainOption && $this->multiLangConfig['defaultLang']) {
                    $data[] = [
                        $this->multiLangConfig['langTableFk'] => $mainOption['id'],
                        $this->multiLangConfig['langTableLanguageColumn'] => $this->multiLangConfig['defaultLang'],
                        $this->valueColumn => $mainOption[$this->valueColumn],
                    ];

                    foreach ($optionLangValues as $langCode => $val) {
                        $data[] = [
                            $this->multiLangConfig['langTableFk'] => $mainOption['id'],
                            $this->multiLangConfig['langTableLanguageColumn'] => $langCode,
                            $this->valueColumn => $val,
                        ];
                    }
                }

            }
        }

        if (!empty($data)) {
            \Yii::$app->db->createCommand()
                ->batchInsert(
                    $this->multiLangConfig['langTable'],
                    [
                        $this->multiLangConfig['langTableFk'],
                        $this->multiLangConfig['langTableLanguageColumn'],
                        $this->valueColumn
                    ],
                    $data
                )
                ->execute();
        }
    }

    protected function loadMultiLangAttributes()
    {
        if ($this->isEavMultiLang()) {

            $this->multiLangOptions = (new Query())
                ->select(
                    [
                        $this->multiLangConfig['langTableFk'],
                        $this->multiLangConfig['langTableLanguageColumn'],
                        $this->multiLangConfig['langTable'] . '.' . $this->valueColumn,
                        $this->tableName . '.' . $this->attributeColumn
                    ]
                )
                ->from($this->multiLangConfig['langTable'])
                ->join(
                    'INNER JOIN',
                    $this->tableName,
                    $this->multiLangConfig['langTable'] . '.'.$this->multiLangConfig['langTableFk'].' = ' . $this->tableName . '.id'
                )
                ->where([$this->tableName . '.' . $this->entityColumn => $this->owner->{$this->pk}])
                ->all();

            $data = [];
            foreach ($this->multiLangOptions as $mOption) {
                if ($mOption[$this->multiLangConfig['langTableFk']] != $this->multiLangConfig['defaultLang']) {
                    $data[$mOption[$this->attributeColumn]][$mOption[$this->multiLangConfig['langTableLanguageColumn']]] = $mOption[$this->valueColumn];
                }
            }

            $this->multiLangOptions = $data;
        }
    }

    /**
     *
     */
    protected function deleteOldRecords()
    {
        \Yii::$app->db->createCommand()
            ->delete($this->tableName, [$this->entityColumn => $this->owner->{$this->pk}])
            ->execute();
    }
}
