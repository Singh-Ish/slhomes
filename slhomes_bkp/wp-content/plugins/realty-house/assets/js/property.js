"use strict";
function geocodePosition(pos) {
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode
	({
			latLng: pos
		},
		function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				jQuery("#property_address").val(results[0].formatted_address);
			}
			else {
				jQuery("#property_address").val();
			}
		}
	);
}

function initializeSubmit() {

	var myLatLng   = new google.maps.LatLng((realty_house_proeprty.propertyLat ? realty_house_proeprty.propertyLat : 40.6700 ), (realty_house_proeprty.propertyLong ? realty_house_proeprty.propertyLong : -73.9400 ));
	var mapOptions = {
		zoom:               12,
		center:             myLatLng,
		// Extra options
		//mapTypeControl: false,
		panControl:         false,
		zoomControlOptions: {
			style:    google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		}
	};
	var map        = new google.maps.Map(document.getElementById('property_map'), mapOptions);
	var image      = realty_house_plg.plg_base + 'assets/img/marker.png';

	var marker = new google.maps.Marker({
		position:  myLatLng,
		map:       map,
		draggable: true,
		icon:      image
	});
	if (jQuery('#property_address').length > 0) {
		var input        = document.getElementById('property_address');
		var autocomplete = new google.maps.places.Autocomplete(input);
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var place = autocomplete.getPlace();
			jQuery('#property_latitude').val(place.geometry.location.lat());
			jQuery('#property_longitude').val(place.geometry.location.lng());
			marker.setPosition(place.geometry.location);
			map.setCenter(place.geometry.location);
			map.setZoom(15);
		});
	}
	google.maps.event.addListener(marker, 'dragend', function (event) {
		geocodePosition(marker.getPosition());
		jQuery('#property_latitude').val(event.latLng.lat());
		jQuery('#property_longitude').val(event.latLng.lng());
	});
}

jQuery.fn.digits = function () {
	return this.each(function () {
		jQuery(this).next().text(jQuery(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
	})
};

jQuery(document).ready(function ($) {
	initializeSubmit();
	jQuery('#property_price').on('keyup', function () {
		jQuery(this).digits();
	});

	jQuery('#property_agents').select2().on("select2:select", function (evt) {
		var element  = evt.params.data.element;
		var $element = jQuery(element);

		$element.detach();
		jQuery(this).append($element);
		jQuery(this).trigger("change");
	});

	jQuery('.repeatable-add').click(function () {
		var field      = jQuery(this).next('ul').children('li').clone(true),
			// fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last'),
			fieldIndex = jQuery('.custom_repeatable li').length;

		var firstField = jQuery(this).closest('td').find('.custom_repeatable').append(field);
		firstField.find('li').last().find('input').each(function (index) {
			var _this = jQuery(this);
			_this.attr('name', _this.attr('id') + "[" + fieldIndex + "][" + _this.data('name') + "]");
		});

		return false;
	});

	jQuery('.repeatable-remove').click(function () {
		jQuery(this).parent().remove();
		return false;
	});

	jQuery('.custom_repeatable').sortable({
		opacity: 0.6,
		revert:  true,
		cursor:  'move',
		handle:  '.sort'
	});


	jQuery('.table-title').on('click', function () {
		jQuery(this).toggleClass('open').next('.table-container').slideToggle();
	});


	// Gallery Uploader

	// Uploading files
	var slideshow_frame;

	jQuery('.add_slideshow_images').on('click', function (event) {

		event.preventDefault();

		var _this                      = jQuery(this),
			image_slideshow_ids        = _this.prev('input'),
			currentSliderShowContainer = _this.parents('.ravis_slideshow_wrapper'),
			attachment_ids             = image_slideshow_ids.val();

		// Create the media frame.
		slideshow_frame = wp.media.frames.downloadable_file = wp.media({
			// Set to true to allow multiple files to be selected
			multiple: true
		});

		// When an image is selected, run a callback.
		slideshow_frame.on('select', function () {
			var selection = slideshow_frame.state().get('selection');

			selection.map(function (attachment) {
				attachment = attachment.toJSON();
				if (attachment.id) {
					attachment_ids = (jQuery.trim(attachment_ids) != '' ? jQuery.trim(attachment_ids) + "---" + attachment.id : attachment.id);
					if (currentSliderShowContainer.hasClass('attachments')) {
						currentSliderShowContainer.children('ul').append('<li class="image" data-attachment_id="' + attachment.id + '">' + attachment.filename + '<span><a href="#" class="delete_slide"><i class="dashicons dashicons-no"></i></a></span></li>');
					}
					else {
						currentSliderShowContainer.children('ul').append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment.url + '" /><span><a href="#" class="delete_slide"><i class="dashicons dashicons-no"></i></a></span></li>');
					}
				}
			});
			image_slideshow_ids.val(attachment_ids);
		});

		// Finally, open the modal
		slideshow_frame.open();

	});

	// Remove files
	jQuery('.ravis_slideshow_wrapper').on('click', 'a.delete_slide', function () {

		var _this                      = jQuery(this),
			currentSliderShowContainer = _this.parents('.ravis_slideshow_wrapper'),
			attachment_ids             = '',
			i                          = 0;

		_this.closest('.image').remove();
		currentSliderShowContainer.find('.image').each(function () {
			var attachment_id = jQuery(this).attr('data-attachment_id');
			attachment_ids    = ( i == 0 ? attachment_id : attachment_ids + '---' + attachment_id);
			i++;
		});

		currentSliderShowContainer.find('input[type="hidden"]').val(attachment_ids);
		return false;

	});

	jQuery('#property_property_status').on('change', function () {
		var target = jQuery('#property_price_type');
		jQuery(this).val() == realty_house_plg.rent_id ? target.removeClass('hidden') : target.addClass('hidden');
	});

	jQuery('td.more-details').on('click', function (e) {
		e.preventDefault();
		var _this = jQuery(this);
		_this.toggleClass('active').parent('tr').next().fadeToggle();
	});

	jQuery('.offer-delete').on('click', function (e) {
		e.preventDefault();
		var _this    = jQuery(this),
			id       = _this.data('id'),
			pID      = _this.data('p-id'),
			security = _this.data('security');

		jQuery.ajax({
			type:     'POST',
			dataType: 'json',
			url:      realty_house_plg.ajaxurl,
			data:     {
				'action':   'realty_house_price_offer_delete',
				'id':       id,
				'pID':      pID,
				'security': security
			},
			success:  function (data) {
				if (data.status === true) {
					window.location.reload();
				}
				else {
					alert(data.message);
				}
			}
		});
	});
});