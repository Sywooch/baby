<?php
/**
 * @var $category \app\modules\store\models\StoreCategory
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->registerLinkTag(['rel' => 'canonical', 'href' => \yii\helpers\Url::canonical()]);
?>
    <div class="breadcrumb">
        <?= $category->getBreadCrumbs() ?>
    </div>
    <h1><span class="h1-top">Clothes For Girls</span></h1>
    <div class="category-info">
        <p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ulla. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>
    </div>
    <div class="product-filter">
        <div class="display">
            <label>Display:</label>
            <p><span id="list" class="list_on"></span> <span id="grid" onclick="display('grid');"></span></p>
        </div>
        <div class="sort">
            <label>Sort By:</label>
            <select class="selectBox">
                <option value="1" selected="selected">Default</option>
                <option value="2">Name (A - Z)</option>
                <option value="3">Name (Z - A)</option>
                <option value="4">Price (Low &gt; High)</option>
                <option value="5">Price (High &gt; Low)</option>
                <option value="6">Rating (Highest)</option>
                <option value="7">Rating (Lowest)</option>
                <option value="8">Model (A - Z)</option>
                <option value="9">Model (Z - A)</option>
            </select>
        </div>
        <div class="limit">
            <label>Show:</label>
            <select class="selectBox">
                <option value="1" selected="selected">15</option>
                <option value="2">25</option>
                <option value="3">50</option>
                <option value="4">75</option>
                <option value="5">100</option>
            </select>
        </div>
    </div>
    <div class="product-compare"><a href="#" id="compare-total">Product Compare (0)</a></div>
    <div class="product-list">
        <div class="box-product"> <span class="new">Sale</span> <a class="image" href="product.html" title="View more"> <img src="image/ex/p1-small.jpg" alt=""> <span class="new2">Sale</span> </a>
            <div class="list_grid_right">
                <h3 class="name"><a href="product.html" title="">Brown Shoes for Boys</a></h3>
                <p class="wrap_price"> <span class="price-old">$119.50</span> <span class="price-new">$60.75</span> <span class="price-tax">Ex Tax: $50.00</span> </p>
                <p class="description">Tudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</p>
                <p class="submit">
                    <input type="button" value="Add to Cart" class="button">
                </p>
                <p class="links_add"> <a>Add to Wish List</a> <a>Add to Compare</a> </p>
            </div>
        </div>
        <div class="box-product"><a class="image" href="product.html" title="View more"> <img src="image/ex/p2-small.jpg" alt=""></a>
            <div class="list_grid_right">
                <h3 class="name"><a href="product.html" title="">Brown Shoes for Boys</a></h3>
                <p class="wrap_price"> <span class="price">$119.50</span> <span class="price-tax">Ex Tax: $100.00</span> </p>
                <p class="description">Tudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</p>
                <p class="submit">
                    <input type="button" value="Add to Cart" class="button">
                </p>
                <p class="links_add"> <a>Add to Wish List</a> <a>Add to Compare</a> </p>
            </div>
        </div>
        <div class="box-product"><a class="image" href="product.html" title="View more"> <img src="image/ex/p3-small.jpg" alt=""></a>
            <div class="list_grid_right">
                <h3 class="name"><a href="product.html" title="">Brown Shoes for Boys</a></h3>
                <p class="wrap_price"> <span class="price">$119.50</span> <span class="price-tax">Ex Tax: $100.00</span> </p>
                <p class="description">Tudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</p>
                <p class="submit">
                    <input type="button" value="Add to Cart" class="button">
                </p>
                <p class="links_add"> <a>Add to Wish List</a> <a>Add to Compare</a> </p>
            </div>
        </div>
        <div class="box-product last-item row-first"><a class="image" href="product.html" title="View more"> <img src="image/ex/p4-small.jpg" alt=""></a>
            <div class="list_grid_right">
                <h3 class="name"><a href="product.html" title="">Brown Shoes for Boys</a></h3>
                <p class="wrap_price"> <span class="price">$119.50</span> <span class="price-tax">Ex Tax: $100.00</span> </p>
                <p class="description">Tudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</p>
                <p class="submit">
                    <input type="button" value="Add to Cart" class="button">
                </p>
                <p class="links_add"> <a>Add to Wish List</a> <a>Add to Compare</a> </p>
            </div>
        </div>
    </div>
    <div class="pagination">
        <div class="results">Showing 1 to 4 of 4 (1 Pages)</div>
    </div>
<?php
/*echo \frontend\components\CatalogListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}{empty_items}',
    'itemOptions' => ['class' => 'catalog-item'],
    'viewParams' => ['class' => 'front-product-preview'],
    'itemView' => '_item',
    'options' => ['class' => 'catalog catalog-nth4 tt-effect-scalerotate tt-effect-delay clearfix']
]);

echo \app\modules\store\widgets\pager\LinkPager::widget([
    'pagination'=>$dataProvider->pagination,
]);*/
?>
