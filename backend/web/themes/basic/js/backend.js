//General
$(function () {
	if ($('.s_name').length) {
		$('.form-vertical').liTranslit();
	}

	$(document).on("click", '.ajax-link', function(event) {
		event.preventDefault();
		var that = this;
		var url = $(that).attr('href');
		var data = $(that).data('params');

		executeAjaxRequest(url, data);
	});

	$(document).on('change', '.dependent', function (event) {
		event.preventDefault();
		var that = this;
		var url = $(that).data('url');
		var name = $(that).data('name');
		jQuery.ajax({
			'cache': false,
			'type': 'POST',
			'dataType': 'json',
			'data': name+'='+that.value,
			'success':
				function (response) {
					parseResponse(response);
				}, 'error': function (response) {
				alert(response.responseText);
			}, 'beforeSend': function () {
			}, 'complete': function () {
			}, 'url': url});
	});

	$(document).on('click', '.btn-template-builder', function (event) {
		event.preventDefault();
		var that = this;
		var url = $(that).data('url');
		var val = $('.template-builder select').val();
		jQuery.ajax({
			'cache': false,
			'type': 'POST',
			'dataType': 'json',
			'data': 'type='+val,
			'success':
				function (response) {
					parseResponse(response);
					initBlogArticleContentSorting();
				}, 'error': function (response) {
				alert(response.responseText);
			}, 'beforeSend': function () {
			}, 'complete': function () {
			}, 'url': url});
	});

	$(document).on('click', '.btn-template-delete', function(event) {
		event.preventDefault();

		if (confirm("Подтвердите удаление блока")) {
			$(this).parent('.content-append').remove();
		}

	});

	$(document).on('change', '.product-attribute-type', function (event) {
		toggleProductAttributeType();
	});

	$(document).on('change', '.config-type', function (event) {
		event.preventDefault();
		var that = this;
		var url = $(that).data('url');
		var form = $(this).parents('form');
		var action = form.attr('action');

		jQuery.ajax({
			'cache': false,
			'type': 'POST',
			'dataType': 'json',
			'data': form.serialize()+'&action='+action,
			'success':
				function (response) {
					parseResponse(response);
				}, 'error': function (response) {
				alert(response.responseText);
			}, 'beforeSend': function () {
			}, 'complete': function () {
			}, 'url': url});
	});

	$(document).on('change', '.order-product-sku', function (event){
		event.preventDefault();

		var el = $(this).parents('tr');

		updateOrderProductPrice(el);
	});

	$(document).on('blur', '.order-product-qnt', function (event){
		event.preventDefault();

		var el = $(this).parents('tr');

		updateOrderProductPrice(el);
	});

	$(document).ready(function () {
		initMainPageRefresh();
		initNewOrderCountCheck();
		toggleProductAttributeType();
		initStoreProductImageSorting();
		initCategorySorting();
		styleBlogContentBuilderBlock();
		initBlogArticleContentSorting();
	});
});

//Multi-upload widget
$(function(){
	$(document).ready(function(){
		fixMultiUploadImageCropUrl();
	});

	$(document).on('click', '.save-cropped', function(){
		event.preventDefault();
		var that = this;
		var url = $(that).attr('href');
		var data = {
			startX: $('#dataX').val(),
			startY: $('#dataY').val(),
			width: $('#dataWidth').val(),
			height: $('#dataHeight').val(),
			fileId: $('#fileId').val()
		};

		jQuery.ajax({
			'cache': false,
			'type': 'POST',
			'dataType': 'json',
			'data':'data='+JSON.stringify(data),
			'success':
				function (response) {
					parseResponse(response);
				}, 'error': function (response) {
				alert(response.responseText);
			}, 'beforeSend': function () {
			}, 'complete': function () {
			}, 'url': url});

	});

	$(document).on('click', '.cancel-crop', function(){
		event.preventDefault();

		hideModal('.modal');
	});

	$('.modal').on('hidden.bs.modal', function (e) {
		$(this).removeData('bs.modal');
	})

	$('.modal').on('shown.bs.modal', function (e) {
		var $dataX = $("#dataX"),
			$dataY = $("#dataY"),
			$dataHeight = $("#dataHeight"),
			$dataWidth = $("#dataWidth");

		$(".img-container > img").cropper({
			aspectRatio: 215 / 245,
			preview: ".img-preview",
			done: function(data) {
				$dataX.val(Math.round(data.x));
				$dataY.val(Math.round(data.y));
				$dataHeight.val(Math.round(data.height));
				$dataWidth.val(Math.round(data.width));
			}
		});
	});
});

function hideModal(elem)
{
	$(elem).modal('hide');
}

function parseResponse(response) {
	if (response.replaces instanceof Array) {
		for (var i = 0, ilen = response.replaces.length; i < ilen; i++) {
			$(response.replaces[i].what).replaceWith(response.replaces[i].data);
		}
	}
	if (response.append instanceof Array) {
		for (i = 0, ilen = response.append.length; i < ilen; i++) {
			$(response.append[i].what).append(response.append[i].data);
		}
	}
	if (response.content instanceof Array) {
		for (i = 0, ilen = response.content.length; i < ilen; i++) {
			$(response.content[i].what).html(response.content[i].data);
		}
	}
	if (response.js) {
		$("body").append(response.js);
	}
	if (response.refresh) {
		window.location.reload(true);
	}
	if (response.redirect) {
		window.location.href = response.redirect;
	}
}


function toggleProductAttributeType() {
	if ($('.product-attribute-type').length) {
		var val = $('.product-attribute-type').val();

		if (val == 2 || val == 3) {
			$('.tab_1').show();
		}
		else {
			$('.tab_1').hide();
		}
	}
}

function initStoreProductImageSorting()
{
	if ($('.file-preview-thumbnails').length) {
		$('.file-preview-thumbnails').sortable({
			update: function (event, ui) {
				saveStoreProductSort();
			}
		});
	}
}

function saveStoreProductSort()
{
	var url = $('#urlForSorting').val();
	var data = $(".kv-file-remove.btn").map(
		function () {return $(this).data('key');}
	).get().join(",");


	jQuery.ajax({
		'cache': false,
		'type': 'POST',
		'dataType': 'json',
		'data': 'sort='+data,
		'success':
			function (response) {
				parseResponse(response);
			}, 'error': function (response) {
			alert(response.responseText);
		}, 'beforeSend': function () {
		}, 'complete': function () {
		}, 'url': url});
}

function initCategorySorting()
{
	if ($('.category-sortable').length) {

		$('.category-sortable').nestedSortable({
			placeholder : "ui-sortable-placeholder",
			start: function(e, ui){
				ui.placeholder.height(ui.item.height());
			},
			update: function(event, ui) {

				$.ajax({
					'url': $('.category-sortable').data('url'),
					'type': 'post',
					'data': {'items': JSON.stringify($('.category-sortable').nestedSortable('toArray', {startDepthCount: 1}))},
					'success': function () {
					},
					'error': function (request, status, error) {
						alert(status + ' ' + error);
					}
				});
			},
			handle: '.badge',
			items: '.sort-item',
			listType: 'ul',
			maxLevels: 2,
			protectRoot: true,
			excludeRoot: true
		});
	}

}

function initBlogArticleContentSorting()
{
	if ($('.template-builder').length) {
		$('.template-builder').sortable({
			placeholder: "ui-state-highlight",
			handle: ".btn-template-mover",
			items: ".content-append",
			cancel: ''
		});
	}
}

function fixMultiUploadImageCropUrl()
{
	$('.crop-link').each(function(){
		var href = $(this).attr('href');
		var key = $(this).data('key');
		var isKeyAdded = parseInt(href.match(/\d+/));

		if (key && isNaN(isKeyAdded)) {
			$(this).attr('href', href + key);
		}
	});
}

function styleBlogContentBuilderBlock()
{
	var contentBuilder = $('.template-builder');
	if (contentBuilder.length) {
		contentBuilder.parents('.row').css('background', 'rgb(215, 249, 196)');
	}
}

function executeAjaxRequest(url, data, completeCallback)
{
	var csrfParam = $('meta[name="csrf-param"]').attr('content');
	var csrfToken = $('meta[name="csrf-token"]').attr('content');

	jQuery.ajax({
		'cache': false,
		'type': 'POST',
		'dataType': 'json',
		'data': csrfParam+'='+csrfToken + '&' + data,
		'success':
			function (response) {
				parseResponse(response);
			}, 'error': function (response) {
			alert(response.responseText);
		}, 'beforeSend': function () {
		}, 'complete': completeCallback ? completeCallback :function () {
		}, 'url': url});
}

function getNewOrdersCount()
{
	var csrfParam = $('meta[name="csrf-param"]').attr('content');
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
	var newOrderCount = 0;

	jQuery.ajax({
		'cache': false,
		'async': false,
		'type': 'POST',
		'dataType': 'json',
		'data': csrfParam+'='+csrfToken,
		'success':
			function (response) {
				newOrderCount = response;
			}, 'error': function (response) {
			alert(response.responseText);
		}, 'beforeSend': function () {
		}, 'complete': function () {
		}, 'url': '/site/get-new-requests-count'});

	return newOrderCount;
}

function initMainPageRefresh()
{
	if ($('#refreshButton').length) {
		setInterval(function () {
			$("#refreshButton").click();
		}, 60000);
	}
}

function initNewOrderCountCheck()
{
	Tinycon.setOptions({
		width: 7,
		height: 10,
		font: '11px arial'

	});
	var key = $('#pusher_key');

	if (key.length) {
		var pusher = new Pusher(key.val(), { encrypted: true });
		var channel = pusher.subscribe('order_count_channel');

		channel.bind('new_order', function(data) {
			Tinycon.setBubble(data.message);
		});

		Tinycon.setBubble(getNewOrdersCount());
	} else {
		console.log('pusher key not found')
	}

}

function updateOrderProductPrice(el)
{
	var price;
	var select = el.find('.order-product-sku');

	if (select.length) {
		price = el.find('.order-product-sku option:selected').data('price');
	} else {
		select = el.find('.order-product-sku-static');
		if (select.length) {
			price = select.data('price');
		} else {
			price = undefined;
		}
	}

	if (price !== undefined) {
		var qnt = el.find('.order-product-qnt').val();
		var total = el.find('.order-product-total');
		var totalSum = parseFloat(price * qnt).toFixed(2);

		el.find('.order-product-price').html(price);
		total.html(isNaN(totalSum) ? '0.00' : totalSum);
	}
}

function addProductAfterSelect2It(obj, url, orderId)
{
	var requestUrl;
	if(obj.added.hasOwnProperty('cert_id')){
		requestUrl = url+'?id='+obj.val+'&orderId='+orderId+'&is_cert=1';
	} else {
		requestUrl = url+'?id='+obj.val+'&orderId='+orderId;
	}

	executeAjaxRequest(
		requestUrl,
		null,
		function(){
			$('input[name=\'product_list\']').select2('data', null);
		}
	);
}
