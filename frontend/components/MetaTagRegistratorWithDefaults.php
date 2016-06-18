<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use app\modules\store\models\StoreProductFilter;
use common\components\PhpMorphy;
use common\models\Language;
use notgosu\yii2\modules\metaTag\models\MetaTag;
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class MetaTagRegistratorWithDefaults
 * @package frontend\components
 */
class MetaTagRegistratorWithDefaults
{
    /**
     * @var string
     */
    public static $title;

    /**
     *
     */
    const PAGE_MAIN = 1;

    /**
     *
     */
    const PAGE_CATALOG_WITHOUT_CATEGORY = 2;

    /**
     *
     */
    const PAGE_CATALOG_WITH_CATEGORY = 3;

    /**
     *
     */
    const PAGE_PRODUCT = 4;

    /**
     *
     */
    const PAGE_NEW_PRODUCT = 5;

    /**
     *
     */
    const PAGE_TOP_50_PRODUCT = 6;

    /**
     *
     */
    const PAGE_GET_GIFT = 7;

    /**
     *
     */
    const PAGE_CHICARDI_CLUB = 8;

    /**
     *
     */
    const PAGE_CHICARDI_CLUB_STATIC = 9;

    /**
     *
     */
    const PAGE_SHOWROOM = 10;

    /**
     *
     */
    const PAGE_GIFT_CERTIFICATE = 11;

    /**
     *
     */
    const PAGE_PAY_AND_DELIVERY = 12;

    /**
     *
     */
    const PAGE_ACTIONS_AND_DISCOUNTS = 13;

    /**
     *
     */
    const PAGE_BLOG_WITH_RUBRIC = 14;

    /**
     *
     */
    const PAGE_BLOG_ARTICLE = 15;

    /**
     *
     */
    const PAGE_ONE_SALE_PAGE = 16;

    /**
     *
     */
    const PAGE_ABOUT_US = 17;

    /**
     * @param ActiveRecord $model
     * @param string $langCode
     * @param $page
     * @param array $metaTagsToFetch
     */
    public static function register(ActiveRecord $model, $langCode = '', $page = null, $metaTagsToFetch = [])
    {
        $fetchedMetaTagList = [];
        $metaTagsForModel = MetaTagContent::find()
            ->where([MetaTagContent::tableName().'.model_id' => $model->id])
            ->andWhere([MetaTagContent::tableName().'.model_name' => (new \ReflectionClass($model))->getShortName()])
            ->joinWith(['metaTag', 'metaTagContentLangs']);

        if (!empty($metaTagsToFetch)) {
            $metaTagsForModel->andWhere(['meta_tag_name' => $metaTagsToFetch]);
        } else {
            $metaTagsForModel->andWhere([MetaTag::tableName().'.is_active' => 1]);
        }

        $metaTagsForModel = $metaTagsForModel->all();

        if (!empty($metaTagsForModel)) {
            foreach ($metaTagsForModel as $metaTag) {
                if (!empty($langCode)) {
                    $langValues = ArrayHelper::map($metaTag->metaTagContentLangs, 'lang_id', 'meta_tag_content');
                    $content = isset($langValues[$langCode]) ? $langValues[$langCode] : '';
                } else {
                    $content = $metaTag->meta_tag_content;
                }
                if (!empty($content)) {
                    if (strtolower($metaTag->metaTag->meta_tag_name) === 'title') {
                        \Yii::$app->getView()->title = $content;
                    } else {
                        \Yii::$app->view->registerMetaTag(
                            [
                                'name' => $metaTag->metaTag->meta_tag_name,
                                'content' => $content
                            ],
                            $metaTag->metaTag->meta_tag_name
                        );
                    }
                    $fetchedMetaTagList[] = $metaTag->metaTag->meta_tag_name;
                }
            }
        }
        static::registerDefaultMeta($fetchedMetaTagList, $model, $page);
    }

    /**
     * @param array $usedMetaTagList
     * @param $model
     * @param $page
     */
    protected static function registerDefaultMeta(array $usedMetaTagList, $model, $page)
    {
        $defaultTitle = $defaultKeywords = $defaultDesc = null;
        $pageVar = \Yii::$app->request->get('page');
        $isPageAvailable = $pageVar && in_array($page, [
                static::PAGE_CATALOG_WITH_CATEGORY,
            ]);


        if (!in_array('title', $usedMetaTagList)) {
            $defaultTitle = \Yii::$app->config->get('default_meta_title');
        }

        if (!in_array('keywords', $usedMetaTagList)) {
            $defaultKeywords = \Yii::$app->config->get('default_meta_keywords');
        }

        if (!in_array('description', $usedMetaTagList)) {
            $defaultDesc = \Yii::$app->config->get('default_meta_description');
        }

        if ($defaultTitle || $defaultKeywords || $defaultDesc || $isPageAvailable) {
            switch($page){
                case static::PAGE_MAIN:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Main Page');
                    break;
                case static::PAGE_CATALOG_WITHOUT_CATEGORY:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Catalog');
                    break;
                case static::PAGE_CATALOG_WITH_CATEGORY:
                    $parentCategory = $model->getCategoryParent();

                    if ($defaultTitle) {
                        $seoDefaultTitle = \Yii::$app->config->get('default_meta_title_for_other_pages');
                        if ($seoDefaultTitle) {
                            $defaultTitle = $model->label . ' - ' . $seoDefaultTitle;

                            $pageVar = \Yii::$app->request->get('page');
                            if ($pageVar && $pageVar > 1) {
                                $defaultTitle .= ' - ' . \Yii::t('frontend', 'page {n}', ['n' => $pageVar]);
                            }
                        } else {
                            $defaultTitle = $model->label;
                        }
                    }


                    //Если это страница с пагинацией принудительно переопределяем title на указанный в конфиге
                    if ($pageVar) {
                        $configTitle = \Yii::$app->config->get('default_meta_title_for_catalog_category');
                        if ($configTitle) {
                            $defaultTitle = str_replace(
                                ['{{page}}', '{{category}}'],
                                [$pageVar, $model->label],
                                $configTitle
                            );
                        }
                    }

                    $defaultDesc = \Yii::$app->config->get('default_meta_description_for_catalog_category');
                    if ($defaultDesc) {
                        $label = mb_strtolower($model->label, 'UTF-8');
                        $parentCase = empty($model->label_parent_case)
                            ? mb_strtolower($model->label, 'UTF-8')
                            : mb_strtolower($model->label_parent_case, 'UTF-8');

                        $defaultDesc = str_replace(
                            ['{{category}}', '{{category_many}}'],
                            [$label, $parentCase],
                            $defaultDesc
                        );
                    }

                    if ($defaultKeywords) {
                        $keywords = $model->label;
                        if ($parentCategory) {
                            $keywords .= ', ' . $parentCategory;
                        }
                        $keywords .= StoreProductFilter::getFilterLabels();
                        $keywords .= ', '.$defaultKeywords;

                        $defaultKeywords = $keywords;
                    }
                    break;
                case static::PAGE_PRODUCT:
                    $parentCategory = $model->category->getCategoryParent();
                    $defaultTitle = \Yii::$app->config->get('default_meta_title_for_product_page');

                    if ($defaultTitle) {
                        $defaultTitle = $model->label . ' ' . $defaultTitle;
                    } else {
                        $defaultTitle = $model->label;
                    }

                    if ($defaultDesc) {
                        $description = $model->label . ' - ' . $model->category->label;
                        $seoAnnounce = $model->getAnnounceForSeo();
                        if ($seoAnnounce) {
                            $description .= ' - '.$seoAnnounce;
                        }

                        $defaultDesc = $description. ' - ' . $defaultDesc;
                    }


                    if ($defaultKeywords) {
                        $keywords = $model->label . ', ' . $model->category->label;
                        if ($parentCategory) {
                            $keywords .= ', ' . $parentCategory;
                        }

                        $filters = $model->getFiltersLabels();
                        if ($filters) {
                            $keywords .= $filters;
                        }

                        $defaultKeywords = $keywords. ', ' . $defaultKeywords;
                    }
                    break;
                case static::PAGE_NEW_PRODUCT:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'New Products');
                    break;
                case static::PAGE_TOP_50_PRODUCT:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'TOP-50 Products');
                    break;
                case static::PAGE_GET_GIFT:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Get Gift');
                    break;
                case static::PAGE_SHOWROOM:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Show Room');
                    break;
                case static::PAGE_CHICARDI_CLUB:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Chicardi Club Blog');
                    break;
                case static::PAGE_CHICARDI_CLUB_STATIC:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Chicardi Club');
                    break;
                case static::PAGE_PAY_AND_DELIVERY:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Pay And Delivery');
                    break;
                case static::PAGE_ACTIONS_AND_DISCOUNTS:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Actions And Discounts');
                    break;
                case static::PAGE_GIFT_CERTIFICATE:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'Gift Certificate');
                    break;
                case static::PAGE_BLOG_WITH_RUBRIC:
                    if ($defaultTitle) {
                        $title = $model->label;

                        if ($pageVar && $pageVar > 1) {
                            $title .= ' - '. \Yii::t('frontend', 'page {n}', ['n' => $pageVar]);
                        }
                        $title .= ' - '.$defaultTitle;

                        $defaultTitle = $title;
                    }

                    if ($defaultKeywords) {
                        $keywords = $model->label;
                        $keywords .= ', '.$defaultKeywords;

                        $defaultKeywords = $keywords;
                    }

                    if ($defaultDesc) {
                        $description = $model->label;
                        $description .= ' - '.$defaultDesc;

                        $defaultDesc = $description;
                    }
                    break;
                case static::PAGE_BLOG_ARTICLE:
                    if ($defaultTitle) {
                        $title = $model->label. ' - '. $model->blogRubric->label;
                        $title .= ' - ' . $defaultTitle;

                        $defaultTitle = $title;
                    }

                    if ($defaultDesc) {
                        $description = $model->label. ' - '. $model->blogRubric->label;

                        $seoAnnounce = $model->getDescriptionForSeo();
                        if ($seoAnnounce) {
                            $description .= ' - '.$seoAnnounce;
                        }

                        $defaultDesc = $description. ' - ' . $defaultDesc;
                    }


                    if ($defaultKeywords) {
                        $keywords = $model->label . ', ' . $model->blogRubric->label;
                        $keywords .= ', '.$defaultKeywords;

                        $defaultKeywords = $keywords;
                    }
                    break;
                case static::PAGE_ONE_SALE_PAGE:
                    if ($defaultTitle) {
                        $title = $model->label;
                        $title .= ' - '.$defaultTitle;

                        $defaultTitle = $title;
                    }

                    if ($defaultDesc) {
                        $description = $model->label;
                        $seoAnnounce = $model->getDescriptionForSeo();
                        if ($seoAnnounce) {
                            $description .= ' - '.$seoAnnounce;
                        }

                        $defaultDesc = $description. ' - ' . $defaultDesc;
                    }


                    if ($defaultKeywords) {
                        $keywords = $model->label;
                        $keywords .= ', '.$defaultKeywords;

                        $defaultKeywords = $keywords;
                    }
                    break;
                case static::PAGE_ABOUT_US:
                    static::getDefaultMeta($defaultTitle, $defaultDesc, $defaultKeywords, 'About us');
                    break;
            }

            static::registerMetaTags($defaultTitle, $defaultKeywords, $defaultDesc);
        }
    }

    /**
     * @param $defaultTitle
     * @param $defaultDesc
     * @param $defaultKeywords
     * @param $page
     */
    protected static function getDefaultMeta(&$defaultTitle, &$defaultDesc, &$defaultKeywords, $page)
    {
        if ($defaultTitle) {
            $title = \Yii::t('frontend', 'Seo ' . $page . ' Title');
            $pageVar = \Yii::$app->request->get('page');
            $seoDefaultTitle = \Yii::$app->config->get('default_meta_title_for_other_pages');

            if ($seoDefaultTitle) {
                $title .= ' - ' . $seoDefaultTitle;
            } else {
                $title .= ' - ' . $defaultTitle;
            }

            if ($pageVar && $pageVar > 1) {
                $title .= ' - ' . \Yii::t('frontend', 'page {n}', ['n' => $pageVar]);
            }

            $defaultTitle = $title;
        }

        if ($defaultDesc) {
            $description = \Yii::t('frontend', 'Seo '.$page.' Description');
            $description .= ' - '.$defaultDesc;

            $defaultDesc = $description;
        }

        if ($defaultKeywords) {
            $keywords = \Yii::t('frontend', 'Seo '.$page.' Keywords');
            $keywords .= ', '.$defaultKeywords;

            $defaultKeywords = $keywords;
        }
    }

    /**
     * @param $title
     * @param $keywords
     * @param $desc
     */
    protected static function registerMetaTags($title, $keywords, $desc)
    {
        if ($title) {
            static::$title = $title;
            \Yii::$app->getView()->title = $title;
        }

        if ($keywords) {
            \Yii::$app->view->registerMetaTag(
                [
                    'name' => 'keywords',
                    'content' => $keywords
                ],
                'keywords'
            );
        }

        if ($desc) {
            \Yii::$app->view->registerMetaTag(
                [
                    'name' => 'description',
                    'content' => $desc
                ],
                'description'
            );
        }
    }
}
