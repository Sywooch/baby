<?php
/**
 * Author: Pavel Naumenko
 *
 * @var \frontend\components\paymentSystems\interkassa\InterkassaPaymentForm
 */
?>
<form id="pay-form" accept-charset="UTF-8" action="<?php echo $form->getApiUrl(); ?>" method="POST" style="display: none;">
    <?php
    foreach ($form->getAttributes() as $attrName => $attrValue) {
        if ($attrValue != '') {
            echo \yii\helpers\Html::textInput($attrName, $attrValue);
        }
    }
    ?>
</form>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById('pay-form').submit();
    });
</script>
