<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\models;

use himiklab\sortablegrid\SortableGridBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class StoreProductTop
 * @package backend\modules\store\models
 */
class StoreProductNew extends StoreProductSorting
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'new_position'
            ],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find()
            ->with(['mainImage'])
            ->where(['is_new' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['new_position'=> SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['type_id' => $this->type_id]);
        $query->andFilterWhere(['category_id' => $this->category_id]);
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['announce' => $this->announce]);
        $query->andFilterWhere(['content' => $this->content]);
        $query->andFilterWhere(['like', 'sku', $this->sku]);
        $query->andFilterWhere(['price' => $this->price]);
        $query->andFilterWhere(['visible' => $this->visible]);
        $query->andFilterWhere(['position' => $this->position]);
        $query->andFilterWhere(['created' => $this->created]);
        $query->andFilterWhere(['modified' => $this->modified]);

        return $dataProvider;
    }

    /**
     * @return string
     */
    public function getBreadCrumbRoot()
    {
        return 'Сортирова Новинок';
    }
}
