<?php

namespace app\modules\store\controllers;

use app\modules\store\models\StoreCategory;
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductFilterToProduct;
use frontend\components\MetaTagRegistratorWithDefaults;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use frontend\modules\common\models\PageSeo;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Class CatalogController
 *
 * @package app\modules\store\controllers
 */
class CatalogController extends FrontController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'paramsFilter' => [
                'class' => UnusedParamsFilter::className(),
                'actions' => [
                    //action => ['param', 'param2']
                    'index' => ['alias', 'page', 'sort', 'show', 'search'],
                    'search' => ['alias', 'page', 'sort', 'show', 'search'],
                ]
            ],
        ];
    }

    /**
     * @param string $alias
     *
     * @return string
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($alias)
    {
        if (!$alias) {
            throw new NotFoundHttpException;
        }

        return $this->commonAction($alias);
    }

    /**
     * @param $search string
     *
     * @return string
     * @throws HttpException
     */
    public function actionSearch($search)
    {
        $dataProvider = $this->commonAction(null, $search);

        return $this->render('search', [
            'search' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param null|string $alias
     * @param null|string $search
     *
     * @return ActiveDataProvider|string
     * @throws HttpException
     * @throws \yii\base\ExitException
     */
    public function commonAction($alias, $search = null)
    {
        $this->handlePagination();
        $filter = \Yii::$app->request->get('filter');
        $pageSize = (int) \Yii::$app->request->get('show');
        $sort = \Yii::$app->request->get('sort');

        $query = StoreProduct::find()
            ->groupBy(StoreProduct::tableName().'.id')
            ->joinWith(['category', 'mainImage'])
            ->where(StoreProduct::tableName().'.visible = 1');

        if ($search) {
            $query->andWhere(['like', StoreProduct::tableName().'.label', $search]);
        }

        if ($alias) {
            /** @var StoreCategory $category */
            $category = StoreCategory::find()
                ->where(['alias' => $alias, 'visible' => 1])
                ->one();
            if (!$pageSize || $pageSize > 100) {
                $pageSize = 15;
            } elseif ($pageSize == 15) {
                unset($_GET['show']);
                $this->redirect($category->getCatalogUrl($_GET));
                \Yii::$app->end();
            }
            if ($sort === 'default') {
                unset($_GET['sort']);
                $this->redirect($category->getCatalogUrl($_GET));
                \Yii::$app->end();
            }
            $categoryIdList = StoreCategory::getCategoryWithChildIdList($alias);
            if ($categoryIdList) {
                $query->andWhere(['category_id' => $categoryIdList]);
                $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_CATALOG_WITH_CATEGORY;
                $this->modelToFetchSeo = StoreCategory::$category;
            } else {
                throw new HttpException(404, \Yii::t('frontend', 'Can not find such category'));
            }
        } else {
            $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_CATALOG_WITHOUT_CATEGORY;
            $this->modelToFetchSeo = PageSeo::findOne(2);
        }

        if ($filter) {
            $query->joinWith(['storeProductFilterToProducts']);
            $query->andWhere([StoreProductFilterToProduct::tableName().'.filter_id' => $filter]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
                'pageSizeParam' => false,
                'forcePageParam' => false
            ],
            'sort'=> ['defaultOrder' => ['position'=> SORT_DESC]]
        ]);

        if ($search) {
            return $dataProvider;
        }

        return $this->render('index', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    protected function handlePagination()
    {
        $page = \Yii::$app->request->get('page');
        if ($page) {
            //For canonical url
            $this->actionParams = ['page' => $page];

            if ($page === 'all') {
                return $this->redirect(Url::current(['page' => null]));
            }
        }
    }
}
