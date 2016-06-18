<?php
/**
 * Author: Pavel Naumenko
 */
use ijackua\sharelinks\ShareLinks;

?>

<div class="reviews__btns clearfix">
    <a target="_blank" href="<?= $this->context->shareUrl(ShareLinks::SOCIAL_FACEBOOK) ?>"
       class="btn-round btn-round__light-blue"><span>facebook</span></a>
    <a target="_blank" href="<?= $this->context->shareUrl(ShareLinks::SOCIAL_VKONTAKTE) ?>"
       class="btn-round btn-round__blue-vk"><span>vkontakte</span></a>
    <a target="_blank" href="<?= $this->context->shareUrl(ShareLinks::SOCIAL_TWITTER) ?>"
       class="btn-round btn-round__blue-tw"><span>twitter</span></a>
    <a target="_blank" href="<?= $this->context->shareUrl(ShareLinks::SOCIAL_GPLUS) ?>"
       class="btn-round btn-round__red"><span>google+</span></a>
</div>
