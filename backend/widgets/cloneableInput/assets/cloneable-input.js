$(function () {
	$(document).on('click', '.cloneable-item-plus', function (event)
	{
		event.preventDefault();

		var elem = $(this).parents('.input-group').find('input');
		if (elem) {
			var elemToWorkWith = elem.data('item-to-count');
			var elemNameToChange = elem.data('name');
			var maxRowsCount = elem.data('max-rows');
			var elemGroupAppendTo = elem.data('field-to-append');
			var elemCount = $(elemToWorkWith).length;

			if (elemCount < maxRowsCount) {
				var clone = $(this).parents('.input-group').clone();
				clone.find('.cloneable-item-plus').attr('class', 'cloneable-item-minus');
				clone.find('.cloneable-item-minus i').attr('class', 'glyphicon glyphicon-minus');
				clone.find(elemToWorkWith).attr('name', elemNameToChange).val('');

				$(elemGroupAppendTo).append(clone);
			}

		}

		return false;

	});

	$(document).on('click', '.cloneable-item-minus', function (event) {
		event.preventDefault();

		$(this).parents('.input-group').remove();
		return false;
	});


	$(document).on('click', '.cloneable-item-plus-multiple', function (event)
	{
		event.preventDefault();

		var maxArrKey = 0;
		var maxVariantIndex = 0; //суффикс для вариантов
		//Parse max array key
		$('.cloneable input').each(function() {
			var value = parseInt($(this).attr('name').match(/\d+/)[0]);
			maxArrKey = (value > maxArrKey) ? value : maxArrKey;
		});

		//Найдем максимальный суффикс артикула из существующих вариантов
		if ($('.cloneable .variant-sku').length) {
			$('.cloneable .variant-sku').each(function() {
				var skuPrefix = Math.abs(parseInt($(this).val().match(/-(\d+)/)[0]));
				maxVariantIndex = (skuPrefix > maxVariantIndex) ? skuPrefix : maxVariantIndex;
			});
		}

		var elem = $(this).parents('.cloneable').find('input');
		if (elem) {
			var elemToWorkWith = elem.data('item-to-work-with');
			var elemToCount = elem.data('item-to-count');
			var elemCount = $(elemToCount).length;
			var maxRowsCount = elem.data('max-rows');
			var elemGroupAppendTo = elem.data('field-to-append');

			if (elemCount < maxRowsCount) {
				var clone = $(this).parents('.cloneable').clone();
				clone.find('.cloneable-item-plus-multiple').attr('class', 'cloneable-item-minus-multiple');
				clone.find('.cloneable-item-minus-multiple i').attr('class', 'glyphicon glyphicon-minus');
				clone.find('.help-block').text('');

				clone.find(elemToWorkWith).each( function (){
					var nameToChange = $(this).attr('name');
					//For each of cloned inputs increment array key value
					var newName = nameToChange.replace(/\d+/, maxArrKey+1);


					$(this).attr('name', newName);
					if (newName.indexOf("[sku]") >= 0) {
						//Если это вариант, то установим новый артикул для него
						var oldSkuValue = $(this).val();
						if (oldSkuValue != '') {
							var newSkuValue = oldSkuValue.substr(0, oldSkuValue.length - 1);
							//Увеличим суффикс артикула для варианта
							newSkuValue = newSkuValue + (maxVariantIndex+1);
							$(this).attr('value', newSkuValue);
							$(this).val(newSkuValue);
						}
					} else {
						$(this).attr('value', '');
						$(this).val('');
					}
				});

				$(elemGroupAppendTo).append(clone);
			}

		}

		return false;

	});

	$(document).on('click', '.cloneable-item-minus-multiple', function (event) {
		event.preventDefault();

		$(this).parents('.cloneable').remove();
		return false;
	});

});

function initSortable(selector) {
	if ($(selector).length) {
		$(selector).sortable({
			items: ".sortable-item"
		});
	}
}
