<?php
/**
 * Author: Pavel Naumenko
 *
 * @var array $seoText
 */
use yii\helpers\Html;
use app\modules\store\models\StoreProduct;

$StoreProduct = new StoreProduct();

?>
<div class="seo-w hide">
    <div class="seo clearfix">
        <?php
        echo Html::tag(
            'h1',
            isset($seoText['bottom_seo_text_h1'])
                ? $seoText['bottom_seo_text_h1']
                : ''
        );

        echo '<!-- seo_text -->';

        echo Html::tag(
            'h2',
            isset($seoText['bottom_seo_text_h2'])
                ? $seoText['bottom_seo_text_h2']
                : ''
        );
        ?>
        <?php
        echo Html::beginTag('div', ['class' => 'seo-text-w']);
        echo Html::beginTag('div', ['class' => 'seo-text']);
        if (isset($seoText['bottom_seo_block_full'])) {
            echo Html::tag('p', $seoText['bottom_seo_block_full']);
        }
        echo Html::endTag('div');
        echo Html::endTag('div');

        echo '<!-- /seo_text -->';

        echo Html::a(Yii::t('frontend', 'get more'), '#', [
            'class' => 'btn-more-info',
            'data-text-open' => Yii::t('frontend', 'get more'),
            'data-text-close' => Yii::t('frontend', 'hide')

        ]);
        if (strripos($_SERVER['REQUEST_URI'], '/catalog') !== FALSE)
        {
            if (!empty($seoText['bottom_seo_text_h1']))
            {
                echo Html::tag('h2', 'Условия доставки ' . $seoText['bottom_seo_text_h1'] . ': Киев и вся Украина', ['class' => 'h2-top-padding']);
                if (function_exists('seo_shield_init_generate_content')) {
                    $ss_content = seo_shield_init_generate_content(array(
                        'type' => 'products_delivery_block',
                        'markers' => array(
                            // 'get_page_h1' => $seoText['bottom_seo_text_h1'],
                        ), 
                    )); 
                    echo '<p>';
                    if ($ss_content) {  
                        $ss_content->start();
                    }
                    echo '</p>';   
                }
            }
            else
            {
                echo Html::tag('h2', 'Условия доставки ' . $StoreProduct->getCurrentSubCategory() . ': Киев и вся Украина', ['class' => 'h2-top-padding']);
                if (function_exists('seo_shield_init_generate_content')) {
                    $ss_content = seo_shield_init_generate_content(array(
                        'type' => 'products_delivery_block',
                        'markers' => array(
                            'get_page_h1' => $StoreProduct->getCurrentSubCategory(),
                        ), 
                    ));   
                    echo '<p>';
                    if ($ss_content) {  
                        $ss_content->start();
                    }
                    echo '</p>'; 
                }
            }
        }
        ?>
    </div>
    <div class="seo seo-no-border clearfix">
        <?php
        if (strripos($_SERVER['REQUEST_URI'], '/catalog') !== FALSE)
        {
            $random_products = $StoreProduct->getRandomProducts();

            if (!empty($seoText['bottom_seo_text_h1']))
            {
                echo Html::tag('h2', $seoText['bottom_seo_text_h1'] . ' - Купить в Киеве', ['class' => 'h2-top-padding']);
                if (function_exists('seo_shield_init_generate_content')) {
                    $ss_content = seo_shield_init_generate_content(array(
                        'type' => 'category_block',
                        'markers' => array(
                            'GET_PAGE_H1'          => $seoText['bottom_seo_text_h1'],
                            'category_name'        => $StoreProduct->getCurrentSubCategory(),
                            'product_in_catalog_1' => $random_products[0],
                            'product_in_catalog_2' => $random_products[1],
                            'product_in_catalog_3' => $random_products[2],
                            'product_in_catalog_4' => $random_products[3]
                        ), 
                    ));
                    echo '<p>';
                    if ($ss_content) {  
                        $ss_content->start();
                    }
                    echo '</p>';    
                }
            }
            else
            {
                echo Html::tag('h2', $StoreProduct->getCurrentSubCategory() . ' - Купить в Киеве', ['class' => 'h2-top-padding']);
                if (function_exists('seo_shield_init_generate_content')) {
                    $ss_content = seo_shield_init_generate_content(array(
                        'type' => 'category_block',
                        'markers' => array(
                            'GET_PAGE_H1'          => $StoreProduct->getCurrentSubCategory(),
                            'category_name'        => $StoreProduct->getParentCategory(),
                            'product_in_catalog_1' => $random_products[0],
                            'product_in_catalog_2' => $random_products[1],
                            'product_in_catalog_3' => $random_products[2],
                            'product_in_catalog_4' => $random_products[3]
                        ), 
                    ));
                    echo '<p>';
                    if ($ss_content) {  
                        $ss_content->start();
                    }
                    echo '</p>';    
                }
            }
        }
        ?>
    </div>
</div>