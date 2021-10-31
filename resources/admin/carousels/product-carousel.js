import EventBus from "@/admin/EventBus";
import $ from "jquery";

let section_product_query = $('#section_product_query');

EventBus.onChangeSlideType(_type => {
	if ('product-carousel' === _type) {
		section_product_query.slideDown();
	} else {
		section_product_query.hide('fast');
	}
})

const toggleProductCarouselSettings = () => {
	let queryType = $('#_product_query_type').val(),
		_product_query = $('#field-_product_query'),
		_product_categories = $('#field-_product_categories'),
		_product_tags = $('#field-_product_tags'),
		_product_in = $('#field-_product_in'),
		_products_per_page = $('#field-_products_per_page');

	_product_query.hide('fast');
	_product_categories.hide('fast');
	_product_tags.hide('fast');
	_product_in.hide('fast');
	_products_per_page.show('fast');

	if (queryType === 'query_product') {
		_product_query.slideDown();
	}
	if (queryType === 'product_categories') {
		_product_categories.slideDown();
	}
	if (queryType === 'product_tags') {
		_product_tags.slideDown();
	}
	if (queryType === 'specific_products') {
		_product_in.slideDown();
		_products_per_page.hide('fast');
	}
}

$('#_product_query_type').on('change', () => toggleProductCarouselSettings());
$(document).ready(() => toggleProductCarouselSettings());
