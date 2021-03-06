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

function executeAjaxRequest(url, data, completeCallback)
{
	var csrfParam = $('meta[name="csrf-param"]').attr('content');
	var csrfToken = $('meta[name="csrf-token"]').attr('content');

	var postData = csrfParam+'='+csrfToken;
	postData = data ? postData + data : postData;

	jQuery.ajax({
		'cache': false,
		'type': 'POST',
		'dataType': 'json',
		'data': postData,
		'success':
			function (response) {
				parseResponse(response);
			}, 'error': function (response) {
			alert(response.responseText);
		}, 'beforeSend': function () {
		}, 'complete': completeCallback ? completeCallback :function () {
		}, 'url': url});
}

$(document).on('submit', '.ajax-form', function (event) {
	event.preventDefault();
	var that = this;
	jQuery.ajax({
		'cache': false,
		'type': 'POST',
		'dataType': 'json',
		'data': $(that).serialize(),
		'success': function (response) {
			parseResponse(response);
		},
		'error': function (response) {
			alert(response.responseText);
		},
		'beforeSend': function () {
		},
		'complete': function () {
			setTimeout(function () {
				$('.input-search-row').addClass('send');
			}, 2000);
		},
		'url': this.action
	});
	return false;
});

$(document).on("click", '.ajax-link', function (event) {
	event.preventDefault();
	var that = this;
	var url = $(that).attr('href');
	var data = $(that).data('params');

	executeAjaxRequest(url, data);
});

$(document).on("click", '.ajax-popup-link', function (event) {
	event.preventDefault();
	var that = this;
	var url = $(that).attr('href');
	var data = $(that).data('params');

	executeAjaxRequest(url, data, function () {

		if (!$('.popup').is(':empty')) {
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
				content: $('.popup')
			});
		}
	});

	return false;
});
