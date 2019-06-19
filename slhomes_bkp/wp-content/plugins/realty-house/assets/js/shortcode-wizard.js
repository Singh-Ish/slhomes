jQuery(document).ready(function ($) {
	jQuery('title').text('Shortcode Wizard');
	jQuery('#shortcode-item').on('change', function (event) {
		event.preventDefault();
		var shortcodeVal = jQuery(this).val();
		if (jQuery('#' + shortcodeVal).length !== 0) {
			jQuery('#' + shortcodeVal).removeClass('hide').siblings().not('#shortcode-item-container').addClass('hide');
		}
		else {
			jQuery('.no-attributes').removeClass('hide').siblings().not('#shortcode-item-container').addClass('hide');
		}

	});

	jQuery('#shortcode-form').on('submit', function (event) {
		event.preventDefault();
		var newShortcode = '',
			shortCodeVal = jQuery('#shortcode-item').val();

		if (shortCodeVal !== '0') {
			newShortcode += '[' + shortCodeVal;

			jQuery('#' + shortCodeVal + ' .form-item').each(function (index, el) {
				var _this = jQuery(this);
				if (_this.is(':checkbox')) {
					if (_this.is(':checked')) {
						newShortcode += ' ' + _this.attr('name') + '="' + _this.val() + '" ';
					}
				} else {
					if (_this.val() != '') {
						newShortcode += ' ' + _this.attr('name') + '="' + _this.val() + '" ';
					}
				}
			});

			newShortcode += ']';
		}

		// inserts the shortcode into the active editor
		tinyMCE.activeEditor.execCommand('mceInsertContent', 0, newShortcode);

		// closes Thickbox
		tinyMCEPopup.close();
	});
});
