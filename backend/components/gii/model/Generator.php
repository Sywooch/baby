<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\components\gii\model;

use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;

/**
 * Class Generator
 *
 * @package backend\components\gii\model
 */
class Generator extends \yii\gii\generators\model\Generator
{
    public $baseClass = 'backend\components\BackModel';

    public $isThisIsLanguageModel = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['isThisIsLanguageModel', 'boolean']
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'isThisIsLanguageModel' => 'This is language model'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return ArrayHelper::merge(
            parent::hints(),
            [
                'isThisIsLanguageModel' => 'This will generate only required minimum of methods for model'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $className,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$className]) ? $relations[$className] : [],
                'isLangModel' => $this->isThisIsLanguageModel
            ];
            $files[] = new CodeFile(
                \Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php',
                $this->render('model.php', $params)
            );
        }

        return $files;
    }

    /**
     * Generate columns for grid and detail view
     *
     * @param $isView
     * @param \yii\db\ColumnSchema $column
     *
     * @return null|string
     */
    public function generateViewColumns($isView, $column)
    {
        $row = null;

        $name = $column->name;
        if ($isView) {
            switch ($column) {
                case $column->name == 'published':
                    $row = "'published:boolean',";
                    break;
                case $column->name == 'visible':
                    $row = "'visible:boolean',";
                    break;
                case $column->type === 'boolean':
                    $row = "'{$name}:boolean',";
                    break;
                default:
                    $row = "'{$name}',";
                    break;
            }
        } else {
            switch ($column) {
                case ($column->autoIncrement):
                case $column->name == 'position':
                    $row = "[
                    'attribute' => '{$name}',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],";
                    break;
                case (($column->type == 'integer') || ($column->type == 'string' && $column->size && $column->size <= 255)):
                    $row = "'{$name}',";
                    break;
                default:
                    break;
            }
        }
        return $row ? $row . "\n" : $row;
    }

    /**
     * Generate form config row
     *
     * @param \yii\db\ColumnSchema $column
     *
     * @return null|string
     */
    public static function generateFormRow($column)
    {
        $row = null;

        $name = $column->name;
        switch ($column) {
            case ($name === 'id'):
                $row = null;
                break;
            case ($column->type === 'boolean'):
            case ($name === 'published'):
            case ($name === 'visible'):
            case ($column->type === 'smallint' && $column->size === 1):
                $row = "'{$name}' => [
                'type' => Form::INPUT_CHECKBOX,
            ],";
                break;
            case ($name === 'position'):
                $row = "'position' => [
                'type' => Form::INPUT_TEXT,
            ],";
                break;
            case ($column->type === 'integer'):
                $row = "'{$name}' => [
                'type' => Form::INPUT_TEXT,
            ],";
                break;
            case ($column->type === 'string' && $column->dbType === 'date'):
                $row = "'{$name}' => [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => DatePicker::className(),
                    'convertFormat' => true,
                    'options' => [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                        ],
                    ],
                ],";
                break;
            case ($column->dbType === 'text'):
                $row = "'{$name}' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],";
                break;
            default:
                $row = "'{$name}' => [
                'type' => Form::INPUT_TEXT,
            ],";
                break;
        }
        return $row . "\n";
    }
}
