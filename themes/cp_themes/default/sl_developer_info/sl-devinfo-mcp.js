jQuery(document).ready(function() {
	$('#sl-devinfo-jump-nav').submit(function() {
		target = $('#' + $('#sl-devinfo-jump-to').val());
		if (target.length) {
			var offset = target.offset().top;
			$('html,body').animate({scrollTop: offset}, 1000);

			return false;
		}

		return true;
	});

	$('.sl-categories-link').click(showCategories);
	$('.sl-status-link').click(showStatus);
	$('.sl-full-query-link').click(showFullQuery);
	$('.sl-query-link').click(showEntryQuery);

	$(".sl-link").addClass("sl-hidden");
});

function showCategories() {
	$(this).text($(this).text() == 'Show Categories +' ? 'Hide Categories -' : 'Show Categories +');
	$(this).next().slideToggle('fast');
	return false;
}
function showStatus() {
	$(this).text($(this).text() == 'Show Statuses +' ? 'Hide Statuses -' : 'Show Statuses +');
	$(this).next().slideToggle('fast');
	return false;
}
function showFullQuery() {
	$(this).text($(this).text() == 'Show Full Query +' ? 'Hide Full Query -' : 'Show Full Query +');
	$(this).next().slideToggle('fast');
	return false;
}
function showEntryQuery() {
	$(this).text($(this).text() == 'Show Entry Query +' ? 'Hide Entry Query -' : 'Show Entry Query +');
	$(this).next().slideToggle('fast');
	return false;
}