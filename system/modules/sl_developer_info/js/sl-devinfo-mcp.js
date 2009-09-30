jQuery(document).ready(function() {					
	jQuery('#sl-devinfo-jump-nav').submit(function() {
		target = jQuery('#' + jQuery('#sl-devinfo-jump-to').val());
		if (target.length) {
			var offset = target.offset().top;
			jQuery('html,body').animate({scrollTop: offset}, 1000);

			return false;
		}

		return true;
	});					
});