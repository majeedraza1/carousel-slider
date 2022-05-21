import $ from "jquery";

const togglePostCarouselSettings = () => {
	let queryType = $('#_post_query_type').val(),
		_post_date_after = $('#field-_post_date_after'),
		_post_date_before = $('#field-_post_date_before'),
		_post_categories = $('#field-_post_categories'),
		_post_tags = $('#field-_post_tags'),
		_post_in = $('#field-_post_in'),
		_posts_per_page = $('#field-_posts_per_page');

	_post_date_after.hide('fast');
	_post_date_before.hide('fast');
	_post_categories.hide('fast');
	_post_tags.hide('fast');
	_post_in.hide('fast');
	_posts_per_page.show('fast');

	if (queryType === 'date_range') {
		_post_date_after.slideDown();
		_post_date_before.slideDown();
	}
	if (queryType === 'post_categories') {
		_post_categories.slideDown();
	}
	if (queryType === 'post_tags') {
		_post_tags.slideDown();
	}
	if (queryType === 'specific_posts') {
		_post_in.slideDown();
		_posts_per_page.hide('fast');
	}
}

$('#_post_query_type').on('change', () => togglePostCarouselSettings());
$(document).ready(() => togglePostCarouselSettings());
