"use strict";
function featuredProperty() {
	jQuery('.widget[id*="realty_house_featured_properties"]').each(function () {
		var _this = jQuery(this);
		_this.find('select').on('change', function (e) {
			e.preventDefault();
			jQuery(this).val() == 1 ? ( _this.find('.p_id_container').addClass('hidden'), _this.find('.p_count_container').removeClass('hidden') ) : ( _this.find('.p_id_container').removeClass('hidden'), _this.find('.p_count_container').addClass('hidden') );
		});
	});
}
jQuery(document).ready(function ($) {
	jQuery(".datepicker").datepicker({
		dateFormat: "yy-mm-dd"
	});

	jQuery('.custom_upload_image_button').on('click', function (event) {
		event.preventDefault();
		var formfield = jQuery(this).siblings('.custom_upload_image'),
			preview   = jQuery(this).siblings('.custom_preview_image');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		window.send_to_editor = function (html) {

			if (jQuery(html).find("img").length > 0) {
				var imgurl  = jQuery('img', html).attr('src'),
					classes = jQuery('img', html).attr('class'),
					id      = classes.replace(/(.*?)wp-image-/, '');
			} else {
				var imgurl  = jQuery(html).attr('src'),
					classes = jQuery(html).attr('class'),
					id      = classes.replace(/(.*?)wp-image-/, '');
			}

			formfield.val(id);
			preview.attr('src', imgurl);
			tb_remove();
		};
		return false;
	});

	jQuery('.custom_clear_image_button').on('click', function (event) {
		event.preventDefault();
		var defaultImage = jQuery(this).parents('.img_uploader_post_meta').siblings('.custom_default_image').text();
		jQuery(this).parent().siblings('.custom_upload_image').val('');
		jQuery(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);
		return false;
	});

	featuredProperty();
	jQuery(document).ajaxComplete(function () {
		featuredProperty();
	});

	var pageTemplate   = jQuery('#page_template'),
		googleMapBread = jQuery('#google-map-bread');
	if (pageTemplate.length > 0) {
		pageTemplate.on('change', function () {
			var pageTemplateVal = jQuery(this).val();
			if (
				pageTemplateVal == '../templates/property-listing.php' ||
				pageTemplateVal == '../templates/property-listing-rows.php' ||
				pageTemplateVal == '../templates/property-listing-masonry.php' ||
				pageTemplateVal == '../templates/property-bookmark.php'
			) {
				googleMapBread.removeClass('hidden');
			} else {
				googleMapBread.addClass('hidden');
			}
		})
	}
});
