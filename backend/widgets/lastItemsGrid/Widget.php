<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\lastItemsGrid;

use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;

/**
 * Class Widget
 * @package backend\widgets\lastItemsGrid
 */
class Widget extends \yii\base\Widget
{

    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $buttonsPath;

    /**
     * @return string
     */
    public function run()
    {
        $model = $this->model;

        $dataProvider = new ActiveDataProvider([
            'query' => $model::find()->limit(10),
            'pagination' => false,
            'sort'=> ['attributes' => ['id'], 'defaultOrder' => ['id'=> SORT_DESC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $this->model,
            'label' => $this->label,
            'columns' => $this->getViewColumnsWithButtonsRoute(),
            'buttonsPath' => $this->buttonsPath
        ]);
    }

    /**
     *
     */
    protected function getViewColumnsWithButtonsRoute()
    {
        $columns = $this->model->getViewColumns();

        $lastIndex = count($columns) - 1;

        if (isset($columns[$lastIndex]) && isset($columns[$lastIndex]['class']) &&
            $columns[$lastIndex]['class'] == ActionColumn::className()
        ) {
            $columns[$lastIndex]['controller'] = $this->buttonsPath;
        }

        return $columns;
    }
}
