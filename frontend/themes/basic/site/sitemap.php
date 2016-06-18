<?php
use app\modules\store\models\StoreProduct;

?>
<div class="article-w">
	<h1>Карта сайта</h1>
	<div class="article">
		<div class="post">
			<p><a href="/">Главная</a></p>
			<ul>
				<li><a href="/catalog">КАТАЛОГ</a></li>
				<li><a href="/catalog/new">НОВИНКИ</a></li>
				<li><a href="/catalog/top">ТОП-50</a></li>
				<li><a href="/gift-request">ПОДОБРАТЬ ПОДАРОК</a></li>
				<li><a href="/blog">МИР CHICARDI</a></li>
				<li><a href="/showroom">ШОУ-РУМ</a></li>
				<li><a href="/certificate">Подарочные сертификаты</a></li>
				<li><a href="/delivery">Оплата и доставка</a></li>
				<li><a href="/sales">Акции и скидки</a></li>
				<?= StoreProduct::getCategoriesList(); ?>
				<li><a href="/blog/article/zhizn-chicardi-events">Жизнь CHICARDI EVENTS</a></li>
				<li><a href="/catalog/product/skladnoj-zont-blunt-xs-metro-red">Складной зонт BLUNT XS</a></li>
				<li><a href="/sales/view/menjaem-videoobzor-na-skidku">WOW! Постельного белья snurk</a></li>
			</ul>
		</div>
	</div>
</div>