function search(entity) {
	var url = entity.data('url');
	var search = $('input[name=\'search\']').attr('value');
	if (search) {
		if (url.indexOf('?') > -1) {
			url += '&search=' + encodeURIComponent(search);
		} else {
			url += '?search=' + encodeURIComponent(search);
		}
	} else {
		return false;
	}
	window.location = url;
}
$(function () {
	$('#carousel-p ul').jcarousel({
		vertical: false,
		visible: 3,
		scroll: 1
	});
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.4,
		rel: "colorbox"
	});
	$(document).on("click", ".limit-items-selectBox-dropdown-menu li, .sort-items-selectBox-dropdown-menu li", function(e){
		window.location = $(this).find('a').attr('rel');
	});
	$(document).on("click", "#checkout tr.highlight td", function(e){
		$(this).find('input').attr('checked', 1);
	});
	$(document).on("keyup", ".cart-quantity", function(e){
		var quantity = $(this).val();
		if (quantity) {
			var addLink = $('.cart-add');
			if (addLink.length) {
				var href = addLink.data('url') + '&quantity=' + quantity;
				addLink.attr('href', href);
			} else {
				var url = $(this).data('url') + '?quantity=' + quantity;

				executeAjaxRequest(url);
			}
		}
	});
	$('.button-search').bind('click', function() {
		search($(this));
	});

	$('#header input[name=\'search\']').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			search($('.button-search'));
		}
	});
	$(document).ready(function () {
		toggleCartDeliveryOptions();
		initCatalogPjaxEvents();
		calculateOrderSumInTheCart();
		checkCartButton();
		addListNumbers();
	});

	$(document).on("click", ".product-sizes-selectBox-dropdown-menu a", function(e){
		e.preventDefault();
		var id = $(this).attr('rel');
		var option = $('select.product-sizes option[value='+id+']');
		var price = option.data('price');
		var oldPrice = option.data('oldprice');
		$('.price-new').text(price);
		$('.price-old').text(oldPrice);
		var addLink = $('.cart-add');
		if (addLink.length) {
			var href = addLink.data('url');
			var p = href.indexOf("?");
			var num = href.substring(p + "?sizeId=".length);
			var toReplace = 'sizeId=' + num;
			var replaceWith = 'sizeId=' + id;
			href = href.replace(toReplace, replaceWith);
			addLink.attr('href', href);
		}
	});

	//remove product from wish list
	$(document).on('click', '.wish-list-item .btn-delete', function (e) {
		e.preventDefault();
		var that = this;
		executeAjaxRequest($(that).data('href'), null, function(){
			$(that).closest('.wish-list-item').fadeOut(function () {
				$(that).remove();
			})
		});


	});

	$(document).on("click", ".form-submit", function(e){
		e.preventDefault();
		$(this).parents('form').submit();

		return false;
	});

	$(document).on("click", ".busket-w .btn-close-busket-w", function (e) {
		e.preventDefault();
		$("html").removeClass("product-right");
	});

	$(document).on('click', '.count .btn-decrease', function (e) {
		e.preventDefault();
		var el = $(this),
			idProduct = $(this).closest('.purchase-item').attr('data-id'),
			product =  $('[data-id='+idProduct+']'),
			count = product.find('.count b'),
			urlForDecrease = el.data('url');

		if (parseInt(count.html()) >= 2) {
			executeAjaxRequest(urlForDecrease);
		} else {
			return false;
		}
	}).on('click', '.count .btn-increase', function (e) {
		e.preventDefault();
		var el = $(this),
			urlForIncrease = el.data('url');
		executeAjaxRequest(urlForIncrease);
	});

	$(document).on('change', '.delivery .radio-row input', function(event){
		toggleCartDeliveryOptions();
		calculateOrderSumInTheCart();
	})

	$(document).on('DOMSubtreeModified', '.total-sum-confirm b', function(event){
		calculateOrderSumInTheCart();
	})

	$(document).on('change', '.check-date input', function () {
		var variantElem = $('.check-date input:checked').closest('.check-date');
		var variantPrice = variantElem.find('.check-price span').html();
		var variantBuyUrl = variantElem.find('.check-price').data('url');

		$('.product-price .price span').html(variantPrice);

		if (variantBuyUrl) {
			$('.btn-round__purp.ajax-link').attr('href', variantBuyUrl);
		}
	});

	$(document).on('click', '.close-fancybox', function(event) {
		event.preventDefault();

		$.fancybox.close();
	});

});

function calculateOrderSumInTheCart()
{
	var totalSum = parseInt($('.total-sum-confirm b').html());
	if (totalSum) {
		var deliveryTag = $('.delivery-price b');
		var freeDeliveryPrice = parseInt($('input[name="freeDeliveryPrice"]').val());
		var deliveryOptionSelected = $('.delivery .radioclass:checked');
		var deliverySum = (deliveryOptionSelected.length && totalSum < freeDeliveryPrice)
			? parseInt(deliveryOptionSelected.data('sum'))
			: 0;

		var resultSumTag = $('.result-sum .order-form-strong');

		deliveryTag.html(deliverySum);

		resultSumTag.html(parseInt(totalSum + deliverySum));
	}

}

function checkCartButton()
{
	if ($('.buscket-w').length) {
		$('.popup-cart').hide();
	} else {
		$('.popup-cart').show();
	}
}

function showSmallCart()
{
	//Show popup cart only if we are not on the cart page
	if (!$('.buscket-w').length) {
		$("html").addClass("product-right");
	}
}

function toggleCartDeliveryOptions()
{
	var item = $('.delivery-add .delivery-item');
		item.removeClass('delivery-item__show');
		if($('.delivery .courier').prop('checked')){
			$('.delivery-add .courier').addClass('delivery-item__show');
		}
		if($('.delivery .newpost').prop('checked')){
			$('.delivery-add .newpost').addClass('delivery-item__show');
		}
		if($('.delivery .pickup').prop('checked')){
			$('.delivery-add .pickup').addClass('delivery-item__show');
		}
}

function initCatalogPjaxEvents()
{
	var catalog, items, newData, selector;

	$(document).on('pjax:start', function(content, options) {
		if ($('.catalog').length) {
			catalog = $('.catalog');
			items = catalog.find('.catalog-item');
			selector = '.catalog';
		} else {
			if ($('.blog-galery').length) {
				catalog =  $('.blog-galery');
				items = catalog.find('.blog-item');
				selector = '.blog-galery';
			}
		}
		scrollToCatalogBegin();
	});
	$(document).on('pjax:beforeReplace', function(event, data) {
		newData = data.clone();
	});
	$(document).on('pjax:success', function(event, data) {
		$(selector).html(catalog.html());
		var page = getUrlParameter('page');
		var seoTag = $('.seo-page-indicator');
		if (page != undefined && seoTag.length) {
			seoTag.html(page);
		}

		setTimeout(function(){
			animateItems(newData);
		},500)
	});
}

function scrollToCatalogBegin() {

	var whreToSrcollBeforeLoad,
		offset = 0;
	if ($('.catalog-sort').length) {
		whreToSrcollBeforeLoad = $('.catalog-sort');
	} else {
		if ($('.catalog-w').length) {
			whreToSrcollBeforeLoad = $('.catalog-w');
		}
		if ($('.blog-galery').length) {
			whreToSrcollBeforeLoad = $('.blog-galery');
			offset = -40;
		}
		if ($('.catalog-top').length) {
			whreToSrcollBeforeLoad = $('.catalog-top-sort');
		}
	}


	$.scrollTo(whreToSrcollBeforeLoad, 800, {offset: {top: offset}});
}

function addListNumbers()
{
	$('.article ul li').each(function(index){

		$(this).html('<i>' + parseInt(index+1) + '</i>' + $(this).html());
	});
}

function getUrlParameter(sParam)
{
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++)
	{
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam)
		{
			return sParameterName[1];
		}
	}
}

function showPopup()
{
	$.fancybox({
		'content' : $("#popup").html(),
		padding: 0,
		helpers: {
			overlay: {
				css: {
					'background': 'rgba(0, 0, 0, 0.5)'
				}
			}
		}
	});
}

function showPopupW()
{
	if (!$('.popup-w').is(':empty')) {
		$.fancybox({
			padding: 0,
			helpers: {
				overlay: {
					css: {
						'background': 'rgba(0, 0, 0, 0.5)'
					}
				}
			},
			type: 'inline',
			content: $('.popup-w')
		});
	}
}

function modifyCartVideoSkipUrl(url)
{
	$('.order-done-url').attr('href', url);
}
