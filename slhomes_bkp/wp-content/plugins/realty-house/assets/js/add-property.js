"use strict";
(function () {

	function initializeSubmit() {
		var myLatLng   = new google.maps.LatLng(40.6700, -73.9400),
			mapOptions = {
				zoom:   12,
				center: myLatLng,
				// This is where you would paste any style found on Snazzy Maps.
				styles: [{
					"featureType": "water",
					"elementType": "geometry",
					"stylers":     [{"color": "#a0d6d1"}, {"lightness": 17}]
				}, {
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers":     [{"color": "#f2f2f2"}, {"lightness": 20}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers":     [{"color": "#dedede"}, {"lightness": 17}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers":     [{"color": "#dedede"}, {"lightness": 29}, {"weight": 0.2}]
				}, {
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers":     [{"color": "#dedede"}, {"lightness": 18}]
				}, {
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers":     [{"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"featureType": "poi",
					"elementType": "geometry",
					"stylers":     [{"color": "#f1f1f1"}, {"lightness": 21}]
				}, {
					"elementType": "labels.text.stroke",
					"stylers":     [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"elementType": "labels.text.fill",
					"stylers":     [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
				}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
					"featureType": "transit",
					"elementType": "geometry",
					"stylers":     [{"color": "#f2f2f2"}, {"lightness": 19}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers":     [{"color": "#fefefe"}, {"lightness": 20}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers":     [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
				}],

				// Extra options
				//mapTypeControl: false,
				panControl:         false,
				zoomControlOptions: {
					style:    google.maps.ZoomControlStyle.SMALL,
					position: google.maps.ControlPosition.LEFT_BOTTOM
				}
			},
			map        = new google.maps.Map(document.getElementById('p-map'), mapOptions),
			image      = realtyHouse.assetsURL + 'img/marker.png',
			geocoder   = new google.maps.Geocoder();


		var marker = new google.maps.Marker({
			position:  myLatLng,
			map:       map,
			draggable: true,
			icon:      image
		});
		if (jQuery('#p-address').length > 0) {
			var input        = document.getElementById('p-address');
			var autocomplete = new google.maps.places.Autocomplete(input);
			google.maps.event.addListener(autocomplete, 'place_changed', function () {
				var place = autocomplete.getPlace();
				jQuery('#p-lat').val(place.geometry.location.lat());
				jQuery('#p-long').val(place.geometry.location.lng());
				marker.setPosition(place.geometry.location);
				map.setCenter(place.geometry.location);
				map.setZoom(15);
			});
		}
		google.maps.event.addListener(marker, 'dragend', function (event) {
			geocoder.geocode({
				latLng: marker.getPosition()
			}, function (responses) {
				if (responses && responses.length > 0) {
					jQuery('#p-address').val(responses[0].formatted_address);
				} else {
					jQuery('#p-address').val('');
				}
			});

			jQuery('#p-lat').val(event.latLng.lat());
			jQuery('#p-long').val(event.latLng.lng());
		});
	}

	jQuery.fn.digits = function () {
		return this.each(function () {
			jQuery(this).next().find('.digit').text(jQuery(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
		})
	};

	jQuery(document).ready(function () {


		jQuery('#p-status, #p-type').listbox();
		jQuery('.lbjs .lbjs-list').mCustomScrollbar({
			scrollButtons: {enable: true}
		});

		var dynamicText  = jQuery('#p-main-info').find('.p-dynamic-text'),
			pTypeField   = jQuery('#p-type'),
			pStatusField = jQuery('#p-status');
		dynamicText.find('.p-type-text').text(pTypeField.find("option:selected").text());
		dynamicText.find('.p-status-text').text(pStatusField.find("option:selected").text());

		pTypeField.on('change', function () {
			dynamicText.find('.p-status-text').text(jQuery(this).find("option:selected").text());
		});
		pStatusField.on('change', function () {
			jQuery(this).val() == realtyHouse.rent_id ? jQuery('.p-price-type-container').slideDown() : jQuery('.p-price-type-container').slideUp();
			dynamicText.find('.p-type-text').text(jQuery(this).find("option:selected").text());
		});

		//	Initial Maps and auto complete section
		initializeSubmit();

		jQuery('#p-price').on('keyup', function () {
			jQuery(this).digits();
		});

		jQuery('#add-gallery').on('click', function (event) {
			var file_frame; // variable for the wp.media file_frame
			event.preventDefault();

			if (file_frame) {
				file_frame.open();
				return;
			}

			file_frame = wp.media.frames.file_frame = wp.media({
				multiple: true
			});

			file_frame.on('select', function () {
				var attachment       = file_frame.state().get('selection').toJSON(),
					galleryContainer = jQuery('#gallery-uploader'),
					imgField         = jQuery('#p-gallery'),
					imgIDs           = imgField.val();
				for (var i = 0, len = attachment.length; i < len; i++) {
					if (attachment[i].type == 'image') {
						var previewBox =
								'<div class="preview-box" data-attachment-id="' + attachment[i].id + '">' +
								'<i class="fa fa-remove"></i>' +
								'<img src="' + attachment[i].url + '"/>' +
								'</div>';
						galleryContainer.append(previewBox);
						imgIDs += attachment[i].id + '---';
					}
				}
				imgField.val(imgIDs);

				jQuery('.preview-box').on('click', 'i', function (e) {
					e.preventDefault();
					var _this        = jQuery(this),
						id           = _this.parent('.preview-box').data('attachment-id'),
						hiddenInput  = _this.parents('.uploader-container').find('input[type=hidden]'),
						selectedImgs = hiddenInput.val();
					_this.parent('.preview-box').remove();
					hiddenInput.val(selectedImgs.replace(id + '---', ''));
				})
			});
			file_frame.open();
		});

		jQuery('#add-floor-plan').on('click', function (event) {
			var file_frame; // variable for the wp.media file_frame
			event.preventDefault();

			if (file_frame) {
				file_frame.open();
				return;
			}

			file_frame = wp.media.frames.file_frame = wp.media({
				multiple: true
			});

			file_frame.on('select', function () {
				var attachment       = file_frame.state().get('selection').toJSON(),
					galleryContainer = jQuery('#floor-plan-uploader'),
					imgField         = jQuery('#p-floor-plan'),
					imgIDs           = imgField.val();
				for (var i = 0, len = attachment.length; i < len; i++) {
					if (attachment[i].type == 'image') {
						var previewBox =
								'<div class="preview-box" data-attachment-id="' + attachment[i].id + '">' +
								'<i class="fa fa-remove"></i>' +
								'<img src="' + attachment[i].url + '"/>' +
								'</div>';
						galleryContainer.append(previewBox);
						imgIDs += attachment[i].id + '---';
					}
				}
				imgField.val(imgIDs);

				jQuery('.preview-box').on('click', 'i', function (e) {
					e.preventDefault();
					var _this        = jQuery(this),
						id           = _this.parent('.preview-box').data('attachment-id'),
						hiddenInput  = _this.parents('.uploader-container').find('input[type=hidden]'),
						selectedImgs = hiddenInput.val();
					_this.parent('.preview-box').remove();
					hiddenInput.val(selectedImgs.replace(id + '---', ''));
				})
			});
			file_frame.open();
		});

		jQuery('#add-attachment').on('click', function (event) {
			var file_frame; // variable for the wp.media file_frame
			event.preventDefault();

			if (file_frame) {
				file_frame.open();
				return;
			}

			file_frame = wp.media.frames.file_frame = wp.media({
				multiple: true
			});

			file_frame.on('select', function () {
				var attachment          = file_frame.state().get('selection').toJSON(),
					attachmentContainer = jQuery('#attachment-uploader'),
					atchField           = jQuery('#p-attachment'),
					atchIDs             = atchField.val();
				for (var i = 0, len = attachment.length; i < len; i++) {

					var previewBox =
							'<div class="preview-box attachment-preview ' + attachment[i].subtype + '" data-attachment-id="' + attachment[i].id + '">' +
							attachment[i].filename +
							'<i class="fa fa-remove"></i>' +
							'</div>';
					attachmentContainer.append(previewBox);
					atchIDs += attachment[i].id + '---';
				}
				atchField.val(atchIDs);

				jQuery('.preview-box').on('click', 'i', function (e) {
					e.preventDefault();
					var _this        = jQuery(this),
						id           = _this.parent('.preview-box').data('attachment-id'),
						hiddenInput  = _this.parents('.uploader-container').find('input[type=hidden]'),
						selectedImgs = hiddenInput.val();
					_this.parent('.preview-box').remove();
					hiddenInput.val(selectedImgs.replace(id + '---', ''));
				})
			});
			file_frame.open();
		});

		jQuery('#add-neighborhood').on('click', function (e) {
			e.preventDefault();
			var _this        = jQuery(this),
				neighborTmpl = jQuery('#neighborhood-tmpl').clone().removeAttr('id'),
				ulbox        = _this.prev('ul');

			ulbox.append(neighborTmpl);
			ulbox.sortable();

			jQuery('.neighborhood-table').find('li').on('click', '.delete', function (e) {
				e.preventDefault();
				jQuery(this).parent('li').remove();
			});
		});

		jQuery('#add-nearby-schools').on('click', function (e) {
			e.preventDefault();
			var _this        = jQuery(this),
				neighborTmpl = jQuery('#nearby-schools-tmpl').clone().removeAttr('id'),
				ulbox        = _this.prev('ul');

			ulbox.append(neighborTmpl);
			ulbox.sortable();

			jQuery('.nearby-schools-table').find('li').on('click', '.delete', function (e) {
				e.preventDefault();
				jQuery(this).parent('li').remove();
			});
		});

		jQuery('#add-facts').on('click', function (e) {
			e.preventDefault();
			var _this        = jQuery(this),
				neighborTmpl = jQuery('#facts-tmpl').clone().removeAttr('id'),
				ulbox        = _this.prev('ul');

			ulbox.append(neighborTmpl);
			ulbox.sortable();

			jQuery('.facts-table').find('li').on('click', '.delete', function (e) {
				e.preventDefault();
				jQuery(this).parent('li').remove();
			});
		});

		jQuery('#submit-property-form').on('submit', function (e) {
			e.preventDefault();
			var _this          = jQuery(this),
				requiredFields = _this.find('[data-required]'),
				agreementBox   = jQuery('#p_agreement'),
				successMessage = jQuery('#success-property-submit'),
				errorHappens   = false;

			_this.addClass('loading');

			requiredFields.each(function () {

				var $this = jQuery(this);

				if ($this.val() == '' || $this.val() == null) {
					$this.parents('td').addClass('has-alert').prev('.title').addClass('alert-color');
					jQuery('html, body').stop().animate({scrollTop: $this.parents('td').offset().top - 100}, 2000);
					errorHappens = true;
					_this.removeClass('loading');
					return false;
				} else {
					$this.parents('td').removeClass('has-alert').prev('.title').removeClass('alert-color');
					_this.removeClass('loading');
				}
			});

			if (errorHappens == false) {

				if (!agreementBox.is(':checked')) {
					agreementBox.parents('td').addClass('has-alert').prev('.title').addClass('alert-color');
					jQuery('html, body').stop().animate({scrollTop: agreementBox.parents('td').offset().top - 100}, 2000);
					_this.removeClass('loading');
					return false;
				}
			}

			var o = {},
				a = _this.serializeArray();
			jQuery.each(a, function () {
				if (o[this.name] !== undefined) {
					if (!o[this.name].push) {
						o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || '');
				} else {
					o[this.name] = this.value || '';
				}
			});

			var data           = {
					action:     "realty_house_insert_property",
					dataFields: JSON.stringify(o)
				},
				formMessageBox = jQuery('#form-message-box');

			jQuery.ajax({
				type:     "POST",
				url:      realty_house_add_property.ajaxurl,
				data:     data,
				dataType: "json",
				success:  function (data) {
					if (data.status == false) {
						if (data.errorNo == 1) {
							requiredFields.each(function () {
								var $this = jQuery(this);
								if ($this.val() == '' || $this.val() == null) {
									$this.parents('td').addClass('has-alert').prev('.title').addClass('alert-color');
								} else {
									$this.parents('td').removeClass('has-alert').prev('.title').removeClass('alert-color');
								}
							});
							if (!agreementBox.is(':checked')) {
								agreementBox.parents('td').addClass('has-alert').prev('.title').addClass('alert-color');
							} else {
								agreementBox.parents('td').removeClass('has-alert').prev('.title').removeClass('alert-color');
							}

							formMessageBox.addClass('alert alert-danger active').text(data.text);
						}
						if (data.errorNo == 2) {
							formMessageBox.addClass('alert alert-danger active').text(data.text);
						}
					}
					else {
						successMessage.addClass('active');
						_this.text('');
						jQuery('html, body').stop().animate({scrollTop: successMessage.offset().top - 100}, 2000);
					}
				}
			});
			_this.removeClass('loading');
		});
	});
})();