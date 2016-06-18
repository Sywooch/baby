<?php
/**
 * Author: Pavel Naumenko
 */
use app\modules\store\models\StoreCategory;
use app\modules\store\models\StoreProductCartPosition;
use yii\helpers\Url;

$cart = Yii::$app->cart;
$isEmpty = $cart->getIsEmpty();
?>
<div class="btn-close-busket-w">
    <a class="btn-delete" href="#"></a>
</div>
<div class="busket-w_i">
    <div class="btn-close-busket-w btn-close-busket-w__mob">
        <a class="btn-delete" href="#">

        </a>
    </div>
    <p class="busket-title">
        <b class="<?= $isEmpty ? 'title-hide': '' ?>"><?= Yii::t('frontend', 'cart'); ?></b>
        <b class="<?= $isEmpty ? '': 'title-hide' ?>"><?= Yii::t('frontend', 'your cart is empty'); ?></b>
        <span><?= count($cart->getPositions()); ?></span>
    </p>

    <?= $this->context->renderPartial('_cart_positions'); ?>
    <?php if ($isEmpty) { ?>
        <div class="payable-w">
            <table>
                <tr>
                    <td>
                        <?= Yii::t('frontend', 'You have no added products') ?>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        </div>
        <a class="btn-square" href="<?= Url::to(StoreCategory::getCatalogRoute()) ?>">
            <span><?= Yii::t('frontend', 'to catalog') ?></span>
        </a>
    <?php } else { ?>
        <div class="payable-w">
            <table>
                <tr>
                    <td>
                        <i class="icon-buscket"></i>
                        <?= Yii::t('frontend', 'To pay') ?>
                    </td>
                    <td class="total-price">
                        <span><?= $cart->getCost(); ?></span>
                        грн
                    </td>
                </tr>
            </table>
        </div>
        <a class="btn-square" href="<?= StoreProductCartPosition::getShowCartUrl() ?>">
            <span><?= Yii::t('frontend', 'to order form') ?></span>
        </a>
    <?php } ?>
</div>
