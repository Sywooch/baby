<?php
use app\modules\common\forms\NewsSubscribeForm;
use frontend\assets\GMapAsset;
use frontend\assets\HeadAsset;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
/*HeadAsset::register($this);
GMapAsset::register($this);*/
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html class="vis-block" lang="ru">
    <?= $this->render('@frontend/themes/basic/layouts/_head'); ?>
    <body>
    <?php $this->beginBody() ?>
    <div id="container">
        <div id="header">
            <div id="logo"><a href="<?= Url::home() ?>"><img src="/image/logo.png" title="Things for Cuties" alt="Things for Cuties"></a></div>
            <div id="header_right">
                    <div id="language">
                    </div>
                <div id="search">
                    <div class="button-search"></div>
                    <input type="text" name="search" placeholder="Search" value="">
                </div>
                <div id="cart">
                    <div class="heading">
                        <div class="cart_top_in">
                            <h4>Shopping Cart</h4>
                            <a><span id="cart-total">2 item(s) - $369.00</span></a></div>
                    </div>
                    <div class="content">
                        <div class="mini-cart-info">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="image"><a href="product.html"><img src="image/ex/pro-1.png" alt="" title=""></a></td>
                                    <td class="name"><a href="product.html">Scitote</a>
                                        <div> </div></td>
                                    <td class="quantity">x&nbsp;1</td>
                                    <td class="total">$236.99</td>
                                    <td class="remove"><img src="image/remove-small.png" alt="Remove" title="Remove"></td>
                                </tr>
                                <tr>
                                    <td class="image"><a href="product.html"><img src="image/ex/pro-2.png" alt="" title=""></a></td>
                                    <td class="name"><a href="product.html">Retrorsum</a>
                                        <div> </div></td>
                                    <td class="quantity">x&nbsp;1</td>
                                    <td class="total">$60.75</td>
                                    <td class="remove"><img src="image/remove-small.png" alt="Remove" title="Remove"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mini-cart-total">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="right"><b>Sub-Total:</b></td>
                                    <td class="right">$249.99</td>
                                </tr>
                                <tr>
                                    <td class="right"><b>Eco Tax (-2.00):</b></td>
                                    <td class="right">$4.00</td>
                                </tr>
                                <tr>
                                    <td class="right"><b>VAT (17.5%):</b></td>
                                    <td class="right">$43.75</td>
                                </tr>
                                <tr class="last_item">
                                    <td class="right"><b>Total:</b></td>
                                    <td class="right">$297.74</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="checkout"><a class="button mr" href="shoppingcart.html">View Cart</a><a class="button" href="checkout.html">Checkout</a></div>
                    </div>
                </div>
                <div id="bottom_right">
                    <p id="welcome"><?= Yii::t('front', 'Welcome to our baby shop!') ?></p>
                </div>
            </div>
        </div>
        <?= \frontend\widgets\mainMenu\Widget::widget() ?>
        <?php if (in_array(Yii::$app->controller->id, ['catalog', 'product'])) {
            echo $this->render('//layouts/_sidebar');
        } ?>
        <div id="content">
            <?= $content ?>
        </div>
    </div>

    <?= $this->render('@frontend/themes/basic/layouts/_footer'); ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
