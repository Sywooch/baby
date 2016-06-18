<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\widgets\bannerList;

use app\modules\banner\models\Banner;
use backend\modules\store\models\StoreCategory;
use yii\db\Query;

/**
 * Class Widget
 *
 * @package app\modules\banner\widgets\headBannerSlider
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var int|null
     */
    public $categoryAlias = null;

    /**
     * @var int|null
     */
    public $location = null;

    /**
     * @var int|null
     */
    public $type = null;

    /**
     * @var bool
     */
    public $doNotUseDefaultBanner = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        //For seo purposes h1 turn to span
        $tagForBannerHeader = 'span';

        $banners = Banner::find();
        $location = $this->location ? $this->location : \common\models\Banner::LOCATION_TOP;

        if ($location == \common\models\Banner::LOCATION_TOP) {
            $view = 'page_small_top';
        } else {
            $view = 'page_bottom';
        }

        switch ($this->type) {
            case \common\models\Banner::TYPE_CATEGORY:
                $category = (new Query())
                    ->from(StoreCategory::tableName())
                    ->where('alias = :alias', [':alias' => $this->categoryAlias])
                    ->select('id')
                    ->column();

                if ($category) {
                    $banners = $banners->where(['type' => \common\models\Banner::TYPE_CATEGORY])
                        ->andWhere(['category_id' => $category])
                        ->limit(1);
                } else {
                    $banners = $banners->andWhere(['type' => \common\models\Banner::TYPE_CATALOG]);
                }
                break;
            case \common\models\Banner::TYPE_MAIN_PAGE:
                $banners = $banners->where(['type' => \common\models\Banner::TYPE_MAIN_PAGE]);
                if ($location == \common\models\Banner::LOCATION_TOP) {
                    $view = 'page_top';
                }
                break;
            case \common\models\Banner::TYPE_NEW_PRODUCTS:
                $banners = $banners->where(['type' => \common\models\Banner::TYPE_NEW_PRODUCTS]);
                break;
            case \common\models\Banner::TYPE_TOP_50:
                $banners = $banners->where(['type' => \common\models\Banner::TYPE_TOP_50]);
                break;
            case \common\models\Banner::TYPE_BLOG:
                $banners = $banners->where(['type' => \common\models\Banner::TYPE_BLOG]);
                if ($this->location == \common\models\Banner::LOCATION_BOTTOM) {
                    $view = 'blog_bottom';
                } else {
                    $view = 'page_small_top';
                }
                break;
            default:
                return null;
                break;
        }

        $banners = $banners->andWhere(['banner_location' => $location])
            ->andWhere(['visible' => 1])
            ->orderBy('position DESC')
            ->all();

        if (empty($banners)) {
            if ($location == \common\models\Banner::LOCATION_TOP) {
                return $this->render('default', ['tagForBannerHeader' => $tagForBannerHeader]);
            }

            if ($location == \common\models\Banner::LOCATION_BOTTOM && !$this->doNotUseDefaultBanner) {
                $defaultBanner = Banner::find()
                    ->where(['visible' => 1])
                    ->andWhere(['is_default' => 1])
                    ->andWhere(['banner_location' => \common\models\Banner::LOCATION_BOTTOM])
                    ->one();

                if ($defaultBanner) {
                    $banners[0] = $defaultBanner;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }

        return $this->render($view, ['banners' => $banners, 'tagForBannerHeader' => $tagForBannerHeader]);
    }
}
